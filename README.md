 # Hướng dẫn bằng tiếng Việt — WordPress + Sage (Acorn) Docker Starter

Thiết lập môi trường phát triển Docker cho WordPress (PHP) và MySQL, sử dụng theme Sage (với Acorn) để quản lý migrations, models và seeders.

Thư mục chính
- `docker-compose.yml` — Chạy MySQL, WordPress (Apache+PHP) và một container CLI để chạy `composer`/`wp`/`acorn`.
- `wp/` — Điểm mount của WordPress; theme Sage nằm ở `wp/wp-content/themes/sage`.

Yêu cầu
- Docker & Docker Compose
- PowerShell (Windows) để chạy script tự động (có sẵn trong `scripts/setup.ps1`).

Các bước nhanh (PowerShell)

1) Copy file `.env.example` thành `.env` (chỉ cần làm 1 lần):

```powershell
cp .env.example .env
```

2) Khởi chạy Docker (chạy trong thư mục repo):

```powershell
docker-compose up -d
```

3) Hoặc chạy script helper (tự động hóa các bước cài đặt):

```powershell
./scripts/setup.ps1
```

Script sẽ thực hiện: copy `.env` nếu chưa có, chạy `docker-compose up -d`, vào container `cli`, cài `composer install`, chạy `vendor/bin/acorn migrate` và `vendor/bin/acorn db:seed`, sau đó active theme `sage` qua `wp-cli`.

4) Mở trình duyệt: http://localhost:8080 để xem giao diện.

Chi tiết kỹ thuật
- Theme scaffold: `wp/wp-content/themes/sage` có `composer.json` yêu cầu `roots/acorn` và `illuminate/database`.
- Migrations: `wp/wp-content/themes/sage/acorn/migrations` (các file tạo bảng `countries`, `competitions`, `teams`, `matches`).
- Seeders: `wp/wp-content/themes/sage/acorn/seeders` (chèn dữ liệu mẫu để hiện thị giống demo).
- Models: `wp/wp-content/themes/sage/app/Models` (Eloquent models: `Country`, `Competition`, `Team`, `MatchModel`).

Git & Deploy
- Thêm remote GitHub rồi commit & push:

```powershell
git init
git add .
git commit -m "Init: Sage Acorn Docker starter and migrations/seeders"
git remote add origin <your-repo-url>
git branch -M main
git push -u origin main
```
