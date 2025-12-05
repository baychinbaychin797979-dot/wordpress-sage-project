<?php

require_once __DIR__ . '/functions.php';

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Setup Required</title>
</head>

<body>
    <h1>Theme setup required</h1>
    <p>Please run <code>composer install</code> inside <code>wp/wp-content/themes/sage</code> and then run Acorn
        migrations/seeders as described in README.</p>
</body>

</html>
<?php
return;
}

require_once __DIR__ . '/vendor/autoload.php';

if (function_exists('Roots\\bootloader')) {
try {
\Roots\bootloader();
} catch (Throwable $e) {
}
}

if (function_exists('setup_eloquent_connection')) {
setup_eloquent_connection();
}

use App\Models\Competition;
use App\Models\Team;
use App\Models\Country;
use App\Models\MatchModel;

$liveCount = count_live_matches();
$finishedCount = MatchModel::where('status_id', 8)->count();
$scheduleCount = MatchModel::where('status_id', 1)->count();


$statusLabels = [1=>'Not started',2=>'First half',3=>'Half-time',4=>'Second half',5=>'Overtime',6=>'Overtime(deprecated)',7=>'Penalty Shoot-out',8=>'End',9=>'Delay'];

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$matches = [];
try {
$matches = MatchModel::with(['home_team','away_team','home_team.country','away_team.country','home_team.competition','away_team.competition'])->orderBy('match_time','desc')->get();
} catch (Throwable $e) {
error_log('Eloquent error: ' . $e->getMessage());
$matches = [];
}

$groups = [];
foreach ($matches as $m) {
$countryName = $m->home_team && $m->home_team->country ? $m->home_team->country->name : ($m->away_team && $m->away_team->country ? $m->away_team->country->name : 'International');
$compName = $m->home_team && $m->home_team->competition ? $m->home_team->competition->name : ($m->away_team && $m->away_team->competition ? $m->away_team->competition->name : 'Other');
$groups[$countryName][$compName][] = $m;
}

function fmt_time($ts) {
if (!$ts) return '';
return date('H:i', $ts);
}

function score_str($scores, $idx = 0) {
if (!$scores || !is_array($scores)) return '';
return isset($scores[$idx]) && $scores[$idx] !== null ? (string)$scores[$idx] : '';
}

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Live Scores</title>
    <style>
    :root {
        --accent: #d32f2f;
        --muted: #9e9e9e;
        --bg: #f7f7f7;
        --border: #e0e0e0;
        --text: #212121;
        --live-red: #ff0000;
    }

    body {
        font-family: Inter, Arial, sans-serif;
        background: var(--bg);
        margin: 0;
        color: var(--text);
    }

    .container {
        max-width: 1100px;
        margin: auto;
        padding: 16px;
    }

    .tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        align-items: center;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 6px;
        scrollbar-width: thin;
    }

    .tabs::-webkit-scrollbar {
        height: 8px;
    }

    .tabs::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 6px;
    }

    .tab {
        flex: 0 0 auto;
        padding: 8px 14px;
        background: var(--card);
        border-radius: 5px;
        text-decoration: none;
        color: var(--text);
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--border);
        transition: all .18s ease;
        white-space: nowrap;
    }

    .tab:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 15, 15, 0.04);
    }

    .tab.active {
        background: var(--accent);
        color: white;
        border-color: rgba(0, 0, 0, 0.04);
        box-shadow: 0 6px 18px rgba(211, 47, 47, 0.12);
    }



    .country {
        margin-top: 20px;
    }

    .country-header {
        background: #fff;
        border-left: 4px solid var(--accent);
        padding: 10px 12px;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .country-header:before {
        content: "★";
        font-size: 16px;
        color: #bbbbbb;
    }


    .competition {
        margin-top: 6px;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .comp-title {
        padding: 10px 12px;
        display: flex;
        gap: 10px;
        align-items: center;
        font-weight: 700;
        border-bottom: 1px solid var(--border);
        background: #fafafa;
    }

    .comp-title img {
        width: 20px;
        height: 20px;
        border-radius: 3px;
    }


    .match-row {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-top: 1px solid #efefef;
    }

    .left {
        width: 130px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .time {
        font-size: 14px;
        color: var(--muted);
    }

    .status-badge {
        margin-top: 4px;
        font-size: 12px;
        padding: 2px 8px;
        border-radius: 12px;
        background: var(--muted);
        color: white;
    }

    .status-live {
        background: var(--live-red);
    }

    .center {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        flex: 1;
    }

    .team {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .team img {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        object-fit: cover;
    }

    .team-name {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .score {
        display: contents;
        font-size: 18px;
        font-weight: 700;
        min-width: 70px;
        text-align: center;
    }

    .score small {
        display: block;
        font-size: 12px;
        color: var(--muted);
    }

    /* MOBILE RESPONSIVE */

    @media (max-width: 700px) {

        .match-row {
            padding: 10px 6px;
        }

        .left {
            width: 55px;
        }

        .team img {
            width: 24px;
            height: 24px;
        }

        .score {
            min-width: 50px;
            font-size: 15px;
        }

        .comp-title {
            padding: 8px;
            font-size: 14px;
        }

        .country-header {
            font-size: 14px;
        }
    }

    .live-tab {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        /* color: #fff !important;
        background: var(--accent); */
    }

    /* Badge LIVE nhấp nháy */
    .live-badge {
        background: #ff0000;
        color: white;
        font-size: 11px;
        font-weight: 900;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        animation: badgePulse 1.5s infinite;
    }

    /* Vòng tròn đỏ nhấp nháy */
    .live-dot {
        width: 18px;
        height: 18px;

        animation: liveBlink 1.3s infinite ease-in-out;
    }

    .tabs .active .live-dot {
        filter: brightness(0) invert(1);
    }

    /* Hiệu ứng số lượng người đang xem */
    .live-count-number {
        animation: countFlicker 3s infinite;
    }

    /* Keyframes */
    @keyframes liveBlink {
        0% {

            border-radius: 100%;
            box-shadow: 0 0 40px rgba(255, 0, 0, 0.5);
        }

        50% {

            border-radius: 100%;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
        }

        100% {

            border-radius: 100%;
            box-shadow: 0 0 40px rgba(255, 0, 0, 0.5);
        }
    }


    @keyframes badgePulse {

        0%,
        100% {
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.8);
        }

        50% {
            box-shadow: 0 0 30px rgba(255, 0, 0, 1), 0 0 40px rgba(255, 50, 50, 0.8);
        }
    }

    @keyframes countFlicker {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="tabs">
            <a class="tab <?php echo $filter==='all'?'active':'';?>" href="?filter=all">Tất cả</a>

            <a class="tab live-tab <?php echo $filter==='live' ? 'active' : ''; ?>" href="?filter=live">

                <img class="live-dot" src="<?php echo get_theme_file_uri('resources/images/live-i.png'); ?>" alt="Live"
                    style="vertical-align:middle;">

                </span>
                Trực tiếp
                <span class="cont-line">(<span class="live-count-number"><?php echo $liveCount; ?></span>)</span>
            </a>

            <a class="tab <?php echo $filter==='finished'?'active':'';?>" href="?filter=finished">Đã kết thúc</a>
            <a class="tab <?php echo $filter==='schedule'?'active':'';?>" href="?filter=schedule">Lịch thi đấu</a>
        </div>

        <?php foreach ($groups as $country => $comps): ?>
        <div class="country">
            <div class="country-header"><span><?php echo htmlspecialchars($country); ?></span></div>
            <?php foreach ($comps as $compName => $matches): ?>
            <div class="competition">
                <div class="comp-title">
                    <?php
                $logo = null;
                foreach ($matches as $mm) { if ($mm->home_team && $mm->home_team->competition && $mm->home_team->competition->logo) { $logo = $mm->home_team->competition->logo; break;} }
            ?>
                    <?php if($logo):?><img src="<?php echo htmlspecialchars($logo); ?>" alt=""><?php endif;?>
                    <span><?php echo htmlspecialchars($compName); ?></span>
                </div>
                <?php foreach ($matches as $m):
                $isLive = in_array($m->status_id, [2,4,5,7]);
                $isFinished = $m->status_id == 8;
                $isScheduled = $m->status_id == 1;
                if ($filter==='live' && !$isLive) continue;
                if ($filter==='finished' && !$isFinished) continue;
                if ($filter==='schedule' && !$isScheduled) continue;
            ?>
                <div class="match-row">
                    <div class="left">
                        <div class="time"><?php echo fmt_time($m->match_time); ?></div>
                        <?php $isLive = in_array($m->status_id, [2,4,5,7]); ?>
                        <div class="status-badge <?php echo $isLive? 'status-live':''; ?>">
                            <?php echo htmlspecialchars($statusLabels[$m->status_id] ?? ''); ?></div>
                    </div>
                    <div class="center">
                        <div class="team">
                            <?php if($m->home_team && $m->home_team->logo): ?><img
                                src="<?php echo htmlspecialchars($m->home_team->logo); ?>" alt=""><?php else: ?><img
                                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_Bg-qzEs83ULOzPAf81oYzL__xczPMpqs4Q&s' width='36' height='36'></svg>"
                                alt=""><?php endif; ?>
                            <div class="team-name">
                                <?php echo htmlspecialchars($m->home_team ? $m->home_team->name : '—'); ?></div>
                        </div>

                        <div class="score">
                            <?php echo htmlspecialchars(score_str($m->home_scores,0)); ?>
                            <small>-</small>
                            <?php echo htmlspecialchars(score_str($m->away_scores,0)); ?>
                            <?php if(isset($m->home_scores[1]) && $m->home_scores[1] !== null): ?>

                            <?php endif; ?>
                        </div>

                        <div class="team" style="justify-content:flex-end">
                            <?php if($m->away_team && $m->away_team->logo): ?><img
                                src="<?php echo htmlspecialchars($m->away_team->logo); ?>" alt=""><?php else: ?><img
                                src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ed/Logo_JS_Kabylie.svg/250px-Logo_JS_Kabylie.svg.png"
                                alt=""><?php endif; ?>
                            <div class="team-name">
                                <?php echo htmlspecialchars($m->away_team ? $m->away_team->name : '—'); ?></div>
                        </div>
                    </div>
                    <div class="score-ht"> <small>(HT
                            <?php echo (int)$m->home_scores[1]; ?>-<?php echo (int)$m->away_scores[1]; ?>)</small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

    </div>
</body>

</html>