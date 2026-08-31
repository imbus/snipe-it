<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    | The following language lines are used in the user permissions system.
    | Each permission has a 'name' and a 'note' that describes
    | the permission in detail.
    |
    | DO NOT edit the keys (left-hand side) of each permission as these are
    | used throughout the system for translations.
    |---------------------------------------------------------------------------
    */

    'superuser' => [
        'name' => 'Superbenutzer',
        'note' => 'Legt fest, ob der Benutzer vollen Zugriff auf alle Aspekte des Administrators hat. Diese Einstellung überschreibt ALLE spezifischeren und restriktiveren Berechtigungen im gesamten System. ',
    ],
    'admin' => [
        'name' => 'Admin-Zugriff',
        'note' => 'Legt fest, ob der Benutzer Zugriff auf die meisten Aspekte des Systems AUSSER in den Systemeinstellungen hat. Diese Benutzer werden in der Lage sein, Benutzer, Standorte, Kategorien, etc, zu verwalten, aber SIND beschränkt durch die Volle Unterstützung für mehrere Unternehmen, wenn es aktiviert ist.',
    ],

    'import' => [
        'name' => 'CSV-Import',
        'note' => 'Dies wird Benutzern erlauben zu importieren, auch wenn der Zugriff auf Benutzer, Assets, usw. an anderer Stelle verweigert wird.',
    ],

    'reports' => [
        'name' => 'Berichtszugriff',
        'note' => 'Legt fest, ob der Benutzer Zugriff auf den Reports-Abschnitt der Anwendung hat.',
    ],

    'assets' => [
        'name' => 'Assets',
        'note' => 'Gewährt Zugriff auf den Abschnitt Assets der Anwendung. ',
    ],

    'assetsview' => [
        'name' => 'Asset ansehen',
        'note' => 'Note that users with this permission will also be able to see (not modify or delete) files uploaded to the asset model as well. This is to make it easier to share common documents like user manuals across assets without having to upload them to every asset, and to avoid having to grant the user permission to modify asset files.',
    ],

    'assetscreate' => [
        'name' => 'Create New Assets',
    ],

    'assetsedit' => [
        'name' => 'Edit Assets',
    ],

    'assetsdelete' => [
        'name' => 'Delete Assets',
    ],

    'assetscheckin' => [
        'name' => 'Check In',
        'note' => 'Check assets back into inventory that are currently checked out.',
    ],

    'assetscheckout' => [
        'name' => 'Check Out',
        'note' => 'Assign assets in inventory by checking them out.',
    ],

    'assetsaudit' => [
        'name' => 'Audit Assets',
        'note' => 'Allows the user to mark an asset as physically inventoried.',
    ],

    'assetsviewrequestable' => [
        'name' => 'View Requestable Assets',
        'note' => 'Allows the user to view assets that are marked as requestable.',
    ],

    'assetsviewencrypted-custom-fields' => [
        'name' => 'View Encrypted Custom Fields',
        'note' => 'Allows the user to view and modify encrypted custom fields on assets.',
    ],

    'accessories' => [
        'name' => 'Zubehör',
        'note' => 'Gewährt Zugriff auf den Abschnitt Zubehör der Anwendung.',
    ],

    'accessoriesview' => [
        'name' => 'View Accessories',
    ],
    'accessoriescreate' => [
        'name' => 'Create New Accessories',
    ],
    'accessoriesedit' => [
        'name' => 'Edit Accessories',
    ],
    'accessoriesdelete' => [
        'name' => 'Delete Accessories',
    ],
    'accessoriescheckout' => [
        'name' => 'Check Out Accessories',
        'note' => 'Assign accessories in inventory by checking them out.',
    ],
    'accessoriescheckin' => [
        'name' => 'Check In Accessories',
        'note' => 'Check accessories back into inventory that are currently checked out.',
    ],
    'accessoriesfiles' => [
        'name' => 'Zubehördateien verwalten',
        'note' => 'Allows the user to upload, download, and delete files associated with accessories. (This only makes sense with view privileges or higher.)',
    ],

    'assetsfiles' => [
        'name' => 'Manage Asset Files',
        'note' => 'Allows the user to upload, download, and delete files associated with assets. (This only makes sense with view privileges or higher.)',
    ],

    'usersfiles' => [
        'name' => 'Manage User Files',
        'note' => 'Allows the user to upload, download, and delete files associated with users. (This only makes sense with view privileges or higher.)',
    ],

    'modelsfiles' => [
        'name' => 'Manage Model Files',
        'note' => 'Allows the user to upload, download, and delete files associated with asset models on both the model view and the asset view screens. (This only makes sense with view privileges or higher.)',
    ],

    'departmentsfiles' => [
        'name' => 'Manage Department Files',
        'note' => 'Allows the user to upload, download, and delete files associated with departments. (This only makes sense with view privileges or higher.)',
    ],

    'suppliersfiles' => [
        'name' => 'Manage Supplier Files',
        'note' => 'Allows the user to upload, download, and delete files associated with suppliers. (This only makes sense with view privileges or higher.)',
    ],

    'locationsfiles' => [
        'name' => 'Manage Location Files',
        'note' => 'Allows the user to upload, download, and delete files associated with locations.(This only makes sense with view privileges or higher.)',
    ],

    'companiesfiles' => [
        'name' => 'Manage Company Files',
        'note' => 'Allows the user to upload, download, and delete files associated with companies. (This only makes sense with view privileges or higher.)',
    ],

    'consumablesfiles' => [
        'name' => 'Verbrauchsdateien verwalten',
        'note' => 'Allows the user to upload, download, and delete files associated with consumables. (This only makes sense with view privileges or higher.)',
    ],

    'consumables' => [
        'name' => 'Verbrauchsmaterialien',
        'note' => 'Gewährt Zugriff auf den Bereich Verbrauchsmaterialien der Anwendung.',
    ],
    'consumablesview' => [
        'name' => 'View Consumables',
    ],
    'consumablescreate' => [
        'name' => 'Create New Consumables',
    ],
    'consumablesedit' => [
        'name' => 'Edit Consumables',
    ],
    'consumablesdelete' => [
        'name' => 'Delete Consumables',
    ],
    'consumablescheckout' => [
        'name' => 'Check Out Consumables',
        'note' => 'Assign consumables in inventory by checking them out.',
    ],

    'licenses' => [
        'name' => 'Lizenzen',
        'note' => 'Gewährt Zugriff auf den Abschnitt Lizenzen der Anwendung.',
    ],
    'licensesview' => [
        'name' => 'View Licenses',
    ],
    'licensescreate' => [
        'name' => 'Create New Licenses',
    ],
    'licensesedit' => [
        'name' => 'Edit Licenses',
    ],
    'licensesdelete' => [
        'name' => 'Delete Licenses',
    ],
    'licensescheckout' => [
        'name' => 'Lizenzen zuweisen',
        'note' => 'Ermöglicht dem Benutzer, Assets oder Benutzern Lizenzen zuzuweisen.',
    ],
    'licensescheckin' => [
        'name' => 'Unassign Licenses',
        'note' => 'Allows the user to unassign licenses from assets or users.',
    ],
    'licensesfiles' => [
        'name' => 'Manage License Files',
        'note' => 'Allows the user to upload, download, and delete files associated with licenses.',
    ],
    'componentsfiles' => [
        'name' => 'Komponentendateien Verwalten',
        'note' => 'Ermöglicht dem Benutzer das Hochladen, Herunterladen und Löschen in Verbindung mit Komponenten.',
    ],

    'licenseskeys' => [
        'name' => 'Manage License Keys',
        'note' => 'Allows the user to view product keys associated with licenses.',
    ],
    'components' => [
        'name' => 'Komponenten',
        'note' => 'Gewährt Zugriff auf den Bereich "Komponenten" in der Anwendung.',
    ],
    'componentsview' => [
        'name' => 'View Components',
    ],
    'componentscreate' => [
        'name' => 'Create New Components',
    ],
    'componentsedit' => [
        'name' => 'Edit Components',
    ],
    'componentsdelete' => [
        'name' => 'Delete Components',
    ],

    'componentscheckout' => [
        'name' => 'Check Out Components',
        'note' => 'Assign components in inventory by checking them out.',
    ],
    'componentscheckin' => [
        'name' => 'Check In Components',
        'note' => 'Check components back into inventory that are currently checked out.',
    ],
    'kits' => [
        'name' => 'Vordefinierte Kits',
        'note' => 'Gewährt Zugriff auf den Abschnitt "Vordefinierte Kits" in der Anwendung.',
    ],
    'kitsview' => [
        'name' => 'View Predefined Kits',
    ],
    'kitscreate' => [
        'name' => 'Create New Predefined Kits',
    ],
    'kitsedit' => [
        'name' => 'Edit Predefined Kits',
    ],
    'kitsdelete' => [
        'name' => 'Delete Predefined Kits',
    ],
    'users' => [
        'name' => 'Benutzer',
        'note' => 'Gewährt Zugriff auf den Bereich "Benutzer" in der Anwendung.',
    ],
    'predefinedfilters'   => [
        'name' => 'Vordefinierte Filter',
        'note'       => 'Gewährt Zugriff auf Vordefinierte Filter in der App.',
    ],
    'predefinedfiltercreate' => [
        'name' => 'Öffentliche vordefinierte Filter erstellen',
    ],
    'predefinedfilterview' => [
        'name' => 'Öffentliche vordefinierte Filter anzeigen',
    ],
    'predefinedfilteredit' => [
        'name' => 'Öffentliche vordefinierte Filter bearbeiten',
    ],
    'predefinedfilterdelete' => [
        'name' => 'Öffentliche vordefinierte Filter löschen',
    ],
    'usersview' => [
        'name' => 'View Users',
    ],
    'userscreate' => [
        'name' => 'Create New Users',
    ],
    'usersedit' => [
        'name' => 'Edit Users',
    ],
    'usersdelete' => [
        'name' => 'Delete Users',
    ],
    'models' => [
        'name' => 'Modelle',
        'note' => 'Gewährt Zugriff auf den Bereich "Modelle" in der Anwendung.',
    ],
    'modelsview' => [
        'name' => 'View Models',
    ],

    'modelscreate' => [
        'name' => 'Create New Models',
    ],
    'modelsedit' => [
        'name' => 'Edit Models',
    ],
    'modelsdelete' => [
        'name' => 'Delete Models',
    ],
    'categories' => [
        'name' => 'Kategorien',
        'note' => 'Gewährt Zugriff auf den Bereich "Kategorien" in der Anwendung.',
    ],
    'categoriesview' => [
        'name' => 'View Categories',
    ],
    'categoriescreate' => [
        'name' => 'Create New Categories',
    ],
    'categoriesedit' => [
        'name' => 'Edit Categories',
    ],
    'categoriesdelete' => [
        'name' => 'Delete Categories',
    ],
    'departments' => [
        'name' => 'Abteilungen',
        'note' => 'Gewährt Zugriff auf den Bereich "Abteilungen" in der Anwendung.',
    ],
    'departmentsview' => [
        'name' => 'View Departments',
    ],
    'departmentscreate' => [
        'name' => 'Create New Departments',
    ],
    'departmentsedit' => [
        'name' => 'Edit Departments',
    ],
    'departmentsdelete' => [
        'name' => 'Delete Departments',
    ],
    'locations' => [
        'name' => 'Standorte',
        'note' => 'Gewährt Zugriff auf den Bereich "Standorte" in der Anwendung.',
    ],
    'locationsview' => [
        'name' => 'View Locations',
    ],
    'locationscreate' => [
        'name' => 'Create New Locations',
    ],
    'locationsedit' => [
        'name' => 'Edit Locations',
    ],
    'locationsdelete' => [
        'name' => 'Delete Locations',
    ],
    'status-labels' => [
        'name' => 'Statusbezeichnungen',
        'note' => 'Gewährt Zugriff auf den Bereich "Statusbezeichnungen", die für Assets benutzt werden.',
    ],
    'statuslabelsview' => [
        'name' => 'View Status Labels',
    ],
    'statuslabelscreate' => [
        'name' => 'Create New Status Labels',
    ],
    'statuslabelsedit' => [
        'name' => 'Edit Status Labels',
    ],
    'statuslabelsdelete' => [
        'name' => 'Delete Status Labels',
    ],
    'custom-fields' => [
        'name' => 'Benutzerdefinierte Felder',
        'note' => 'Gewährt Zugriff auf den Abschnitt Benutzerdefinierte Felder der Anwendung, die von Assets verwendet wird.',
    ],
    'customfieldsview' => [
        'name' => 'View Custom Fields',
    ],
    'customfieldscreate' => [
        'name' => 'Create New Custom Fields',
    ],
    'customfieldsedit' => [
        'name' => 'Edit Custom Fields',
    ],
    'customfieldsdelete' => [
        'name' => 'Delete Custom Fields',
    ],
    'suppliers' => [
        'name' => 'Lieferanten',
        'note' => 'Gewährt Zugriff auf den Abschnitt Lieferanten der Anwendung.',
    ],
    'suppliersview' => [
        'name' => 'View Suppliers',
    ],
    'supplierscreate' => [
        'name' => 'Create New Suppliers',
    ],
    'suppliersedit' => [
        'name' => 'Edit Suppliers',
    ],
    'suppliersdelete' => [
        'name' => 'Delete Suppliers',
    ],
    'manufacturers' => [
        'name' => 'Hersteller',
        'note' => 'Gewährt Zugriff auf den Abschnitt Hersteller der Anwendung.',
    ],
    'manufacturersview' => [
        'name' => 'View Manufacturers',
    ],
    'manufacturerscreate' => [
        'name' => 'Create New Manufacturers',
    ],
    'manufacturersedit' => [
        'name' => 'Edit Manufacturers',
    ],
    'manufacturersdelete' => [
        'name' => 'Delete Manufacturers',
    ],
    'companies' => [
        'name' => 'Firmen',
        'note' => 'Gewährt Zugriff auf den Bereich Firmen der Anwendung.',
    ],
    'companiesview' => [
        'name' => 'View Companies',
    ],
    'companiescreate' => [
        'name' => 'Create New Companies',
    ],
    'companiesedit' => [
        'name' => 'Edit Companies',
    ],
    'companiesdelete' => [
        'name' => 'Delete Companies',
    ],
    'user-self-accounts' => [
        'name' => 'Benutzerkonten',
        'note' => 'Erlaubt Nicht-Administratoren die Möglichkeit, bestimmte Aspekte ihrer eigenen Benutzerkonten zu verwalten.',
    ],
    'selftwo-factor' => [
        'name' => 'Zwei-Faktor-Authentifizierung verwalten',
        'note' => 'Erlaubt Benutzern die Zwei-Faktor-Authentifizierung für ihre eigenen Konten zu aktivieren, zu deaktivieren und zu verwalten.',
    ],
    'selfapi' => [
        'name' => 'API-Token verwalten',
        'note' => 'Ermöglicht Benutzern, eigene API-Token zu erstellen, anzuschauen und zu widerrufen. Benutzer-Token haben die gleichen Berechtigungen wie der Benutzer, der sie erstellt hat.',
    ],
    'selfedit-location' => [
        'name' => 'Standort Aktualisieren',
        'note' => 'Ermöglicht Benutzern den Standort zu bearbeiten, der mit ihrem eigenen Benutzerkonto verknüpft ist.',
    ],
    'selfcheckout-assets' => [
        'name' => 'Assets Selbst Auschecken',
        'note' => 'Erlaubt es Benutzern Assets ohne Admin-Intervention selbst auszuchecken.',
    ],
    'selfview-purchase-cost' => [
        'name' => 'Einkaufspreis Anzeigen',
        'note' => 'Ermöglicht den Benutzern, den Einkaufspreis von Artikeln in ihrer Account-Ansicht anzuzeigen.',
    ],

    'depreciations' => [
        'name' => 'Abschreibungs-Management',
        'note' => 'Ermöglicht Benutzern das Verwalten und Anzeigen von Vermögensabschreibungsdaten.',
    ],
    'depreciationsview' => [
        'name' => 'View Depreciation Details',
    ],
    'depreciationsedit' => [
        'name' => 'Edit Depreciation Settings',
    ],
    'depreciationsdelete' => [
        'name' => 'Delete Depreciation Records',
    ],
    'depreciationscreate' => [
        'name' => 'Create Depreciation Records',
    ],

    'grant_all' => 'Erteile alle Berechtigungen für :area',
    'deny_all' => 'Verweigere alle Berechtigungen für :area',
    'inherit_all' => 'Alle Berechtigungen für :area von Berechtigungsgruppen vererben',
    'grant' => 'Erteile Berechtigungen für :area',
    'deny' => 'Verweigere Berechtigungen für :area',
    'inherit' => 'Alle Berechtigungen für :area von Berechtigungsgruppen vererben',
    'use_groups' => 'Wir empfehlen dringend, Berechtigungsgruppen zu verwenden, anstatt individuelle Berechtigungen für eine einfachere Verwaltung zuzuweisen.',

];
