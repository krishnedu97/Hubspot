unzip 
create the hubspotsync db in your local.
php artisan migrate

php artisan sync:hubspot-contacts
php artisan serve
View Contacts: Visit http://yourdomain.com/contacts to see the list of contacts.
View Contact Details: Click on a contact's name to view details on http://yourdomain.com/contacts/{id}.
