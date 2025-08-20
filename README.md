# Library Loan System (PHP + MySQL + Bootstrap 5)

Töötab XAMPP-is (Apache + MySQL). Vastab tingimustele: HTML5/CSS3 (Bootstrap 5), PHP+MySQL, kihiline arhitektuur, valideerimised, 2-päevane broneering, 14-päevane laenutus, rollid (user/staff), "mäleta mind" ≥ 4h, CRUD, responsive UI.

## Paigaldus
1. Kopeeri `library-app` XAMPP `htdocs` kausta (nt `C:\xampp\htdocs\library-app`).
2. Käivita XAMPPis **Apache** ja **MySQL**.
3. Ava phpMyAdmin ja **impordi** fail `sql/init_db.sql` (loob `library_db` skeemi ja tabelid, lisab testandmed).
4. Ava fail `includes/config.php` ja kontrolli DB seadeid (kasutaja, parool).
5. Ava brauseris: `http://localhost/library-app/public/`

### Sisselogimiseks (test)
- Kasutaja (user): `user@example.com` / `Password123`
- Töötaja (staff): `staff@example.com` / `Password123`

## Funktsioonid
- Registreerimine, sisselogimine (`remember me` 4h, token DBs).
- Otsing: pealkiri/autor/ISBN.
- Broneerimine (kehtib 2 päeva), laenutamine (max 14 päeva), tagastamine.
- Keeld: ei laenuta, kui on tagastamata laenud.
- Admin (staff): raamatute CRUD, kasutajate haldus, laenutuste ajalugu.
- Serveripoolne valideerimine (PHP), kliendipoolne (JS).
- CSRF kaitse, `password_hash`, prepared statements (PDO).
- Bootstrap 5 (responsive), W3C valideeritav struktuur.

## Kataloog
- `/public` — avalikud vaated (index).
- `/auth` — register/login/logout.
- `/actions` — tegevuste endpointid (reserve/loan/return).
- `/admin` — töötaja vaated.
- `/includes` — andmebaasi ühendus, abifunktsioonid, auth-check, CSRF jt.
- `/templates` — päis/jalus.
- `/sql` — skeem ja testandmed.

## Märkused
- Croni asemel kutsutakse aegunud broneeringute aegumist funktsiooniga `expire_reservations($pdo)` erinevates vaadetes — demo jaoks piisav.
- `config.php` on lihtne (demo); päriselus kasuta .env ja HTTPS cookie secure lippu.
