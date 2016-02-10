<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new KarmaNoteEmailTrigger);
Artisan::add(new WeeklyNoteRequestEmail); 
Artisan::add(new KarmaNoteWeeklyDashboard);  
Artisan::add(new UpdateTestUserKscore);
Artisan::add(new DailyUpdateUser);
Artisan::add(new DailyUpdateKarmacirclesSitemap); 
 