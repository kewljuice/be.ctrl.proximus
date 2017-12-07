#Proximus SMS Provider

## Introduction
Proximus CiviCRM integration: allows delivering SMS messages to mobile phone users through Proximus API.

## Installation
- You can directly clone to your CiviCRM extension directory using<br>
```$ git clone https://github.com/kewljuice/be.ctrl.proximus.git```

- You can also download a zip file, and extract in your extension directory<br>
```$ git clone https://github.com/kewljuice/be.ctrl.proximus/archive/master.zip```

- Configure CiviCRM Extensions Directory which can be done from<br>
```"Administer -> System Settings -> Directories".```

- Configure Extension Resource URL which can be done from<br>
```"Administer -> System Settings -> Resource URLs".```

- The next step is enabling the extension which can be done from<br> 
```"Administer -> System Settings -> Manage CiviCRM Extensions".```

## Configuration

- Configure SMS provider at ```"Administer -> System Settings -> SMS Providers"``` or **yoursite.org/civicrm/admin/sms/provider**. 

| Parameter      	| Value                          	| Required 	|
|----------------	|--------------------------------	|----------	|
| Name           	| Proximus                       	|          	|
| Title          	| Proximus                       	|          	|
| Username       	| Proximus                       	|          	|
| Password       	| [Enter Proximus API key]          | *        	|
| API Type       	| http                           	|          	|
| API Url        	| https://api.ringring.be/sms/V1 	| *        	|
| API Parameters 	| {}                             	|          	|

## FAQ

#### Why don't I receive a mass SMS mailing?

Make sure you have configured the **Send Scheduled SMS** Scheduled Job.

To configure scheduled jobs, go to ```"Administer > System Settings > Scheduled Jobs"```.