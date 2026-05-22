# CiviCRM: Contact Dashboard Tabs

![Screenshot](/images/screenshot.png)

Divides the Contact Dashboard into tabs for ease of use. Also allows display of 
profiles as read-only tabs in the Contact Dashboard.

The extension is licensed under [GPL-3.0](LICENSE.txt).

## Usage

1. Simply enable this extension to cause the sections in the Contact Dashboard
to display as tabs.
2. For more settings and options, navigate to Administer > Customize Data and Screens > Contact Dashboard Tabs. Here you can:
    - Adjust the display order (weight) of dashboard tabs.
    - Click the "Settings" button to access additional options.
3. Optionally, check the "Display on Contact Dashboard?" checkbox on any Profile
to have that Profile also display (as a tab) on the Contact Dashboard.
4. Optionally, where "Display on Contact Dashboard?" is checked for any Profile,
limit the display of this Profile to certain contacts by:
    - selecting one or more contact types in the "Display only for contacts of type(s)" setting to limit display only for contacts of certain type or sub-type.
    - selecting one or more contact types in the "Display only for contacts in group(s)"
    setting to limit display only for contacts who are in one or more groups.

## Caveats

When displaying a Profile on the Contact Dashboard, you should ensure that the 
Profile contains only contact-related fields. For example, contribution-related
fields make no sense in the context of the Contact Dashboard, as there's no specific
contribution being addressed.

## Requirements

* PHP v7.0+
* CiviCRM 5.0

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl com.joineryhq.cdashtabs@https://github.com/twomice/com.joineryhq.cdashtabs/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/twomice/com.joineryhq.cdashtabs.git
cv en cdashtabs
```

## Support

Support for this package is handled under Joinery's ["Active Support" policy](https://joineryhq.com/software-support-levels#active-support).

Public issue queue for this package: https://github.com/twomice/com.joineryhq.cdashtabs/issues
