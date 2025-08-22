RAAMATUKOGU – XAMPP + PHP + MySQL + Bootstrap 5

Kiirkäivitus:
1) Kopeeri kaust 'raamatukogu' -> C:\xampp\htdocs\
2) Käivita XAMPP: Apache + MySQL
3) phpMyAdmin -> loo andmebaas 'raamatukogu' -> Import -> vali 'database.sql'
4) Ava: http://localhost/raamatukogu/public/

Testloginid:
- Staff: staff@example.com / Password123
- User:  user@example.com / Password123

Reeglid:
- Broneering kehtib 2 päeva
- Laenutus 14 päeva
- Laenutus keelatud, kui on tagastamata laen
- Paroolid: password_hash
- Sessioon: vähemalt 4h

Kaustad: public, auth, admin, actions, includes, templates
BASE_URL seadistus: includes/config.php
