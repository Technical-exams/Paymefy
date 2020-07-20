# Elevator Simulator Project
This is a project made on PHP simulating daily movements on an offices building elevators due user calls.  

## FEATURES

**_It's lightweight_**
It uses an _sqlite_ database file to store stats of each elevator which has moved during the day.  
**Does not need a big infrastructure!**  
  
**_It's agnostic_**  
It is coded in PHP, in an agnostic way, even if the release named `v0.2` includes the [_Laravel Framework_](https://laravel.com/). This inclusion does not affect the way the _Elevator Simulator_ has been code. _Laravel_ has been used for serving the stored stats in a user friendly way.  
  
**_It's SOLID_**  
The code follows SOLID principles in >80% of code.  
If don't trust, [just browse it](https://github.com/ProWeb21/elevator/find/master)!

**_It's DDD_ and MSOA**  
The code faces the DDD paradigm applying well-known patterns and types, orchestrated as services injected as dependencies of main application and domain classes. You will find software engineery patterns such _Factories_, _Observer_, _Publisher/Subscriber_, _Strategies_, and class types such _Aggregate-Root Entities_, _DataTransferObjects_, _Command Requests_, _Event Buses_, _Domain Events_, _Domain Services_, _Application Services_, _Repositories_ or _Data Stores_. 

**_It's Hexagonal_**
The code modularizes the application in separate layers (Domain, Application and Infrastructure), placing the _Ports_ as interfaces in the Domain Layer and a few in the Infrastructure layer, and placing _Adapters_ as classes implementing those interfaces in the Infrastructure layer.

## INSTALLATION
### Requirements
As expressed in the [`composer.json` file](https://github.com/ProWeb21/elevator/blob/master/composer.json), it is necessary to have following requirements installed:

- **php** `cli` and `fpm` at version `>=7.2` (get it [here](https://www.php.net/downloads))
- **some enabled extensions for php**:
  * `bcmath`
  * `ctype`
  * `fileinfo`
  * `json`
  * `mbstring`
  * `openssl`
  * `pdo`
  * `sqlite3`
  * `tokenizer`
  * `xml`  
- **composer** tool (get it [here](https://getcomposer.org/))
- **git** (get it [here](https://git-scm.com/downloads))

### Instructions
1. CLONE THE REPO
   At the parent directory where you want to deploy this repo execute the following command in your terminal
   ```
   git clone -b "<release>" https://github.com/ProWeb21/elevator.git "<destination>"
   ``` 
   > _Note_: `<release>` refers to the tagged version of the repo you want to deploy.  
   > Choose `v0.1` for a release runnable with `phpunit` or choose `v0.2` for a release runnable with `artisan` web server.  
   > On the other hand, `<destination>` refers to the name of the destination folder where this repo will be clonned.  
   > No matter if the folder does not exist, it will be created automatically.
2. CREATE YOUR DATABASE
   Once you have clonned the desired version, you will find two database files under the `database` directory.  
   You can either run the [`building.dist.db.sql`](https://github.com/ProWeb21/elevator/blob/master/database/building.dist.db.sql) on a new _Sqlite3_ database file, or just copy the `building.dist.db` file, and rename the copy to a name you like.
3. UPDATE YOUR ENVIRONMENT
   In the `<destination>` folder where this repo has been clonned you will find a `.env` (possibly hidden) file.  
   In this file there must be two _parameter_ entries: `DB_FILE` and `DB_PASSWORD`.  
   Leave empty `DB_PASSWORD` if you are not gonna encrypt your database file (see instruction \#2), do the following way:
   ```
   . . .
   DB_FILE=database/building.db
   DB_PASSWORD=
   . . . 
   ```
   Set `DB_FILE` pointing to the relative path to database file you've created
4. INSTALL REQUIRED PACKAGES
   Just run, from within the `<destination>` folder, the command
   ```
   composer install
   ``` 
   And wait for it to finish

## RUNNING THE APP

### v0.1 PHP UNIT RUNNABLE
At this release the app is run through [_phpunit_](https://phpunit.readthedocs.io/).  
You may run the following command from within the `<destination>` folder:  
```
vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/integration/AgbarSimulator.php   
```

Once it has run, you may see stats data captured during simulation in the table `elevator_stats`.  
Additionaly, there is a view called `elevator_stats_summary` which flattens the data of `elevator_stats`, and offers a summarized version, 
where stats from elevators whose had stopped twice or more in a single minute, are flatten as if them stopped in the latest flat captured, but tracking all their flat movements along that minute.  

So, in the `elevator_stats` table you may find records with the following information:

* `id`: An internal _identifier_ for the elevator stats record
* `time`: Moment in time, during the day when the elevator stats were taken
* `elevator`: Elevator identifier (a simulated manufacturer serial number)
* `stopped_at`: Flat on the building were the elevator was stopped when the stats were taken
* `last_move`: The number of flats the elevator tripped until arrive the flat it was stopped at
* `accum_moves`: The number of flats tripped until that moment in time