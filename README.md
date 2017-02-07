# gameoflife - Vida Gergely

# 1. Fejlesztői környezet kialakítása

Zendet használtam, külön lib könyvtárban helyezkedik el.

Beállítása pl:
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
cd [lib dir]
composer require zendframework/zendframework1

Baállítása:
/gameoflife/index.php - LIBRARY_PATH

# 2. Üzleti logika

/gameoflife/application/library/My/Game.php

# 3. REST API (opcionális)

REST API hívásokkal működik a frontend

# 4. Vizualizáció (Web, Mobil)

a. weben, böngészőben jelenítem meg a generált táblákat

b. a frontend htmlt és jqueryt használ

c. play, stop, és next gombokat lehet használni


# 5. Szerkesztő

a. meg lehet adni a tábla méretét

b. egy cellára kattintva lehet mintát rajzolni

# 6. Minták beolvasása

a. Sajnos lif fájlokat nem olvas be, sima txt-t kezel


# 8. Minták választása felületen

Ki lehet választani hogy melyik mintát szeretném használni
