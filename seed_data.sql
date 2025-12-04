-- Clear existing data
DELETE FROM matches;
DELETE FROM teams;
DELETE FROM competitions;
DELETE FROM countries;

-- Insert countries
INSERT INTO countries (id, name, logo) VALUES
('dz', 'Algeria', NULL),
('in', 'India', NULL),
('bd', 'Bangladesh', NULL);

-- Insert competitions
INSERT INTO competitions (id, name, logo) VALUES
('lnh-dz', 'Giải bóng đá nữ Algeria', NULL),
('u21-dz', 'Liga U21 Youth Algeria', NULL),
('aff-cup', 'Siêu cúp Ấn Độ - Bảng đấu A', NULL),
('bd-league', 'Giải ngoại hạng Bangladesh - Vòng 4', NULL);

-- Insert teams
INSERT INTO teams (id, competition_id, country_id, name, logo) VALUES
('clb-akbou', 'lnh-dz', 'dz', 'CLB nữ Akbou', NULL),
('afak-relizane', 'lnh-dz', 'dz', 'Afak Relizane(w)', NULL),
('cr-belouzza', 'lnh-dz', 'dz', 'CR Belouizzad (W)', NULL),
('ase-alger', 'lnh-dz', 'dz', 'ASE Alger Centre (w)', NULL),
('saoura-u21', 'u21-dz', 'dz', 'Saoura U21', NULL),
('kabylie-u21', 'u21-dz', 'dz', 'Kabylie U21', NULL),
('hyderabad', 'aff-cup', 'in', 'Hyderabad', NULL),
('sreenidi', 'aff-cup', 'in', 'Sreenidi Deccan', NULL),
('fortis', 'bd-league', 'bd', 'Fortis Limited', NULL),
('rahmatgonj', 'bd-league', 'bd', 'Rahmatgonj MFS', NULL),
('sheikh-jamal', 'bd-league', 'bd', 'Sheikh Jamal', NULL),
('bashundhara', 'bd-league', 'bd', 'Bashundhara Kings', NULL);

-- Insert matches
INSERT INTO matches (id, competition_id, home_team_id, away_team_id, status_id, match_time, home_scores, away_scores) VALUES
('m1', 'lnh-dz', 'clb-akbou', 'afak-relizane', 2, 1733331600, '[1, 0]', '[0, 0]'),
('m2', 'lnh-dz', 'cr-belouzza', 'ase-alger', 2, 1733331600, '[2, 1]', '[1, 1]'),
('m3', 'u21-dz', 'saoura-u21', 'kabylie-u21', 2, 1733332200, '[0, 0]', '[4, 2]'),
('m4', 'aff-cup', 'hyderabad', 'sreenidi', 2, 1733328600, '[1, 0]', '[4, 2]'),
('m5', 'bd-league', 'fortis', 'rahmatgonj', 2, 1733327400, '[1, 1]', '[2, 1]'),
('m6', 'bd-league', 'sheikh-jamal', 'bashundhara', 2, 1733327400, '[0, 0]', '[0, 0]');
