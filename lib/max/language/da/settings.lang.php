<?php

/*
  +---------------------------------------------------------------------------+
  | Revive Adserver                                                           |
  | http://www.revive-adserver.com                                            |
  |                                                                           |
  | Copyright: See the COPYRIGHT.txt file.                                    |
  | License: GPLv2 or later, see the LICENSE.txt file.                        |
  +---------------------------------------------------------------------------+
 */

// Installer translation strings
$GLOBALS['strInstall'] = "Installer";
$GLOBALS['strDatabaseSettings'] = "Database opsætning";
$GLOBALS['strAdminSettings'] = "Administrator opsætninger";
$GLOBALS['strAdminAccount'] = "Administrator konto";
$GLOBALS['strAdvancedSettings'] = "Avanceret opsætning";
$GLOBALS['strWarning'] = "Advarsel";
$GLOBALS['strBtnContinue'] = "Fortsæt »";
$GLOBALS['strBtnRecover'] = "Genskab »";
$GLOBALS['strBtnStartAgain'] = "Start opdatering igen »";
$GLOBALS['strBtnGoBack'] = "« Gå tilbage";
$GLOBALS['strBtnAgree'] = "Jeg acceptere »";
$GLOBALS['strBtnDontAgree'] = "« Jeg er uenig";
$GLOBALS['strBtnRetry'] = "Forsøg igen";
$GLOBALS['strWarningRegisterArgcArv'] = "PHP konfigurator variable register_argc_argv skal tændes for at kunne køre vedligeholdelse fra kommando linien.";
$GLOBALS['strTablesType'] = "Tabel type";


$GLOBALS['strRecoveryRequiredTitle'] = "Dit tidligere forsøg på at upgradere udløste en fejl";
$GLOBALS['strRecoveryRequired'] = "Der var en fejl under behandlingen af din tidligere opdatering og {$PRODUCT_NAME} skal forsøge at genskabe opgraderings processen. Venligst klik på Genskab knappen herunder.";

$GLOBALS['strOaUpToDate'] = "Din {$PRODUCT_NAME} database og filstrktur bruger begge den nyeste version of derfor er det ikke nødvendig med at opdatere på dette tidspunkt. Venligst klik 'Fortsæt' for at komme videre til OpenX administrations panelet.";
$GLOBALS['strOaUpToDateCantRemove'] = "Advarsel: UPGRADE filen er stadig inde i din var folder. Vi kan ikke fjerne denne fil på grund af manglede adgang og tilladelse. Venligst slet denne fil selv.";
$GLOBALS['strRemoveUpgradeFile'] = "Du skal fjerne UPGRADE filen fra var folderen.";
$GLOBALS['strDbSuccessIntro'] = "Databasen til {$PRODUCT_NAME} er nu blevet oprettet. Venligst klik på 'Fortsæt' knappen for at komme videre med konfigureringen af {$PRODUCT_NAME} Administrator og Leverings opsætningen";
$GLOBALS['strDbSuccessIntroUpgrade'] = "Dit system er opgraderet succesfuldt. De resterende skærmbilleder vil hjælpe dig med at opdatere konfigurationen af din nye reklame server.";

$GLOBALS['strErrorWritePermissions'] = "Der er fundet nogle fil adgangs fejl, og disse skal repareres inden du kan fortsætte.<br />For at reparere fejlene på en Linux system, prøv at skrive følgende kommando(er):";

$GLOBALS['strErrorWritePermissionsWin'] = "Der er fundet nogle fil adgangs fejl, og disse skal repareres inden du kan fortsætte";
$GLOBALS['strCheckDocumentation'] = "For mere hjælp se <a href='http://{$PRODUCT_DOCSURL}'>{$PRODUCT_NAME} documentation</a>.";

$GLOBALS['strAdminUrlPrefix'] = "Administrator interface URL";



/* ------------------------------------------------------- */
/* Configuration translations                            */
/* ------------------------------------------------------- */

// Global
$GLOBALS['strChooseSection'] = "Vælg sektion";
$GLOBALS['strEditConfigNotPossible'] = "It is not possible to edit all settings because the configuration file is locked for security reasons. " .
    "If you want to make changes, you may need to unlock the configuration file for this installation first.";
$GLOBALS['strEditConfigPossible'] = "It is possible to edit all settings because the configuration file is not locked, but this could lead to security issues. " .
    "If you want to secure your system, you need to lock the configuration file for this installation.";
$GLOBALS['strUnableToWriteConfig'] = "Ude af stand til at skrive ændringer til config filen";
$GLOBALS['strUnableToWritePrefs'] = "Ude af stand til at binde referencer til databasen";
$GLOBALS['strImageDirLockedDetected'] = "Den leverede <b>Billede Mappe</b> er ikke skrivebar af serveren. <br>Du kan ikke fortsætte indtil du enten ændrer adgangstilladdelse til mappen eller opretter mappen.";

// Configuration Settings
$GLOBALS['strConfigurationSetup'] = "Konfigurations tjekliste";
$GLOBALS['strConfigurationSettings'] = "Konfigurations opsætning";

// Administrator Settings
$GLOBALS['strAdministratorSettings'] = "Administrator opsætninger";
$GLOBALS['strAdminUsername'] = "Administrator  brugernavn";
$GLOBALS['strAdminPassword'] = "Administrator  password";
$GLOBALS['strInvalidUsername'] = "Ugyldig brugernavn";
$GLOBALS['strBasicInformation'] = "Basis information";
$GLOBALS['strAdminFullName'] = "Admin's fulde navn";
$GLOBALS['strAdminEmail'] = "Admin's email adresse";
$GLOBALS['strAdministratorEmail'] = "Administrators email adresse";
$GLOBALS['strCompanyName'] = "Virksomheds navn";
$GLOBALS['strUserlogEmail'] = "Log alle udgående email beskeder";
$GLOBALS['strTimezone'] = "Tidszone";
$GLOBALS['strTimezoneEstimated'] = "Estimeret tidszone";
$GLOBALS['strTimezoneGuessedValue'] = "Server tidszone er ikke sat korrekt i PHP";
$GLOBALS['strTimezoneSeeDocs'] = "Venligst se %DOCS% omkring settings variabler for PHP.";
$GLOBALS['strTimezoneDocumentation'] = "dokumentation";
$GLOBALS['strAdminSettingsTitle'] = "Opret en administrator konto";
$GLOBALS['strAdminSettingsIntro'] = "Venligst udfyld denne formlar for at oprette din annonce server adiminstrator konto.";

$GLOBALS['strEnableAutoMaintenance'] = "Automatisk udfør vedligeholdelse under levering if planlagt vedligehold ikke er sat op";

// Database Settings
$GLOBALS['strDatabaseSettings'] = "Database opsætning";
$GLOBALS['strDatabaseServer'] = "Global database server opsætninger";
$GLOBALS['strDbType'] = "Database type";
$GLOBALS['strDbHost'] = "Database host navn";
$GLOBALS['strDbPort'] = "Database port nummer";
$GLOBALS['strDbUser'] = "Database bruger navn";
$GLOBALS['strDbPassword'] = "Database password";
$GLOBALS['strDbName'] = "Database navn";
$GLOBALS['strDatabaseOptimalisations'] = "Database optimiserings opsætning";
$GLOBALS['strPersistentConnections'] = "Brug Persistent tilslutning";
$GLOBALS['strCantConnectToDb'] = "Kan ikke tilslutte til databasen";
$GLOBALS['strDemoDataInstall'] = "Installer demp data";
$GLOBALS['strDemoDataIntro'] = "Standard opsætningsdata kan indlæses i {$PRODUCT_NAME} for at hjælpe dig med servicere online reklamering. De mest almindelige banner typer, så vel som opstarts kampagner kan indlæses og præ-konfigureres. Dette anbefales stærkt for nye installationer.";



// Email Settings
$GLOBALS['strEmailSettings'] = "Email Indstillinger";
$GLOBALS['strEmailHeader'] = "Email Titel";
$GLOBALS['strEmailLog'] = "Email Log";

// Audit Trail Settings
$GLOBALS['strAuditTrailSettings'] = "Handlings Log Indstillinger";
$GLOBALS['strEnableAudit'] = "Aktiver Handlings Log";

// Debug Logging Settings
$GLOBALS['strDebug'] = "Opsætning af debug logning";
$GLOBALS['strProduction'] = "Produktions server";
$GLOBALS['strEnableDebug'] = "Tillad debug logning";
$GLOBALS['strDebugMethodNames'] = "Inkluder metode navn i debug loggen";
$GLOBALS['strDebugLineNumbers'] = "Inkluder linie nummer i degub loggen";
$GLOBALS['strDebugType'] = "Debug log type";
$GLOBALS['strDebugTypeFile'] = "Fil";
$GLOBALS['strDebugTypeSql'] = "SQL database";
$GLOBALS['strDebugName'] = "Debug log navn, kalender, SQL tabel,<br />eller Syslog funktion";
$GLOBALS['strDebugPriority'] = "Debug prioritets niveau";
$GLOBALS['strPEAR_LOG_DEBUG'] = "PEAR_LOG_DEBUG - Informations majoriteten";
$GLOBALS['strPEAR_LOG_INFO'] = "PEAR_LOG_INFO - Standard information";
$GLOBALS['strPEAR_LOG_EMERG'] = "PEAR_LOG_DEBUG - Informations majoriteten";
$GLOBALS['strDebugIdent'] = "Debug identifikations streng";
$GLOBALS['strDebugUsername'] = "mCal, SQL Server brugernavn";
$GLOBALS['strDebugPassword'] = "mCal, SQL Server kodeord";

// Delivery Settings
$GLOBALS['strDeliverySettings'] = "Leverings opsætning";
$GLOBALS['strWebPath'] = "$PRODUCT_NAME Server Access Paths";
$GLOBALS['strWebPathSimple'] = "Web sti";
$GLOBALS['strDeliveryPath'] = "Cache levering";
$GLOBALS['strDeliverySslPath'] = "Cache levering";
$GLOBALS['strTypeFTPUsername'] = "Log ind";
$GLOBALS['strTypeFTPPassword'] = "Kodeord";
$GLOBALS['strTypeFTPErrorNoSupport'] = "Din PHP installation understøtter ikke FTP.";
$GLOBALS['strDeliveryFilenamesAdImage'] = "Tilføj Billede";
$GLOBALS['strDeliveryFilenamesAdJS'] = "Tilføj (JavaScript)";
$GLOBALS['strDeliveryFilenamesAdLayer'] = "Tilføj Layer";
$GLOBALS['strDeliveryFilenamesAdLog'] = "Tilføj Log";
$GLOBALS['strDeliveryFilenamesAdPopup'] = "Tilføj Popup";
$GLOBALS['strDeliveryFilenamesLocal'] = "Lokal Invocation";
$GLOBALS['strDeliveryFilenamesFlash'] = "Flash Inkluderet (Kan være fuldt URL)";
$GLOBALS['strDeliveryCaching'] = "Banner Levering Cache Indstillinger";
$GLOBALS['strDeliveryCacheLimit'] = "Tid imellem Banner Cache Updatering (sekunder)";


$GLOBALS['strOrigin'] = "Brug remote ophavs server";
$GLOBALS['strOriginType'] = "Ophavs server type";
$GLOBALS['strOriginHost'] = "Hostname for ophavs server";


// General Settings

// Geotargeting Settings

// Interface Settings
$GLOBALS['strInventory'] = "Portfolio";
$GLOBALS['strStatisticsDefaults'] = "Statistikker";
$GLOBALS['strConfirmationUI'] = "Bekræftigelse for Bruger Grænseflade";

$GLOBALS['strModesOfPayment'] = "Betalings metode";
$GLOBALS['strHelpFiles'] = "Hjælpe fil";
$GLOBALS['strHasTaxID'] = "Skat ID";

// CSV Import Settings
$GLOBALS['strDefaultConversionStatus'] = "Normal conversions regler";
$GLOBALS['strDefaultConversionType'] = "Normal conversions regler";

/**
 * @todo remove strBannerSettings if banner is only configurable as a preference
 *       rename // Banner Settings to  // Banner Preferences
 */
// Invocation Settings

// Banner Delivery Settings
$GLOBALS['strBannerDelivery'] = "Banner Leverings Indstillinger";

// Banner Logging Settings
$GLOBALS['strBannerLogging'] = "Banner Log Indstillinger";
$GLOBALS['strPreventLogging'] = "Banner Log Indstillinger";

// Banner Storage Settings
$GLOBALS['strBannerStorage'] = "Indstillinger for Banner Lagring";

// Campaign ECPM settings

// Statistics & Maintenance Settings
$GLOBALS['strMaintenanceSettings'] = "Vedligeholdelses Indstillinger";
$GLOBALS['strEmailAddressFrom'] = "Email Adresse rapporter skal sendes FRA";
$GLOBALS['strEmailAddressName'] = "Firma eller navn, email skal underskrives med";

// UI Settings
$GLOBALS['strGeneralSettings'] = "Generel opsætninger";
$GLOBALS['strSSLSettings'] = "SSL Indstillinger";
$GLOBALS['requireSSL'] = "Tving SSL adgang i Bruger Grænseflade";
$GLOBALS['sslPort'] = "SSL Port Brugt af Web Server";




// Regenerate Platfor Hash script

// Plugin Settings

/* ------------------------------------------------------- */
/* Unknown (unused?) translations                        */
/* ------------------------------------------------------- */


