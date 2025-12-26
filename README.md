* Setup steps : 
1) Clone the repository and install dependencies:
--> git clone git@github.com:pranitdalavi/geozones.git
--> cd geozones
--> composer install
--> php artisan key:generate
--> npm install && npm run dev

2) Import geozones_backup.sql into your database
--> e.g. psql -u your_username -d geozones < geozones_backup.sql
--> Note : Please change database name in .env file and username, password according to your system.

* How to run :
1) Execute command in terminal, "php artisan serve"

2) You will see main page, there will be Geozones List item. Please click on that, then ahead geozones creation, edit, list is available.

* How to Test
--> Geozone Creation: Click "Create," draw a polygon on the map (minimum 3 vertices), and assign it a category (e.g., War Risk).

--> Livewire Filtering: On the list page, type a name in the search bar or change the category filter to see the list update dynamically without a page refresh.

--> Spatial Integrity: Edit an existing zone and move the vertices. The system uses ST_IsValid to ensure the geometry remains valid before saving to PostGIS.