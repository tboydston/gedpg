# Good Enough Deterministic Password Generator ( Ged.pg )

Ged.pg generates repeatable, determanistic passwords from a single master password though your terminal. This allows you to create a different password for each site or application you use without relying on 3rd party password managers. 

## Why "Good Enough"

Ged.pg relys on functions such as 'sha256', ASCII conversion of strings, and other basic functions that are availble in all major languages in order to make the results reproducable if one follows the underlining logic. This means avoiding language and environment specific seeded sudo random number generators that might have created more reproducably random results. That said, without knowing your master password it would be functionally impossible to reproduce it from a resulting individual password. Individual passwords are also ordered distinctly enough to make pattern based brute force attacks unrealistic. 

## Using Ged.pg

Create a folder and clone the Ged.pg project. Then navigate to the folder and run gedpg.php

```bash
php gedpg.php
```

You will be prompted for your master password. This password should be long and memorable. 

```bash
Enter password followed by return.
Password:
Confirm password:
```

After confirming your password you will be asked for your password index or if this is a new password you can create a random one by submitting 'r'. 

```bash
Enter password index or type 'r' to return a random index: r
```

Next enter how long you would like the password to be. 

```bash
Enter length of password to generate( Up to 64 ): 16
```

Your new password will be generated and copied to the clipboard( Command+v or Ctrl+v to paste it. ) and can be pasted in the password field. 

You will be provided with the index and length of the password formated as [index]/[length] for example 78152/16 as well as the last 6 characters of the hash used to create the password. **Write these down with the name of the site/service they were used on and save them someplace safe. You will need these to reproduce the password.**  Everytime you generate a password confirm the 6 hash characters are the same. This means the password was generated successfully. 

If you would like to generate another password without re-inputting your master password enter "Y".

```bash
Password copied to clipboard. Index: 7814163/16 End of Hash: afa992
Create Another? [y:n]:
```

If you already have the index and length of a previous password you can generate a password directly buy adding these after the file name in run command. 

```bash
php passGen.php 7814163/16
```

## Aliasing 

If you would like to access Ged.pg directly from the command line using the command "gedpg" you can create an alias by doing the following. 

### Mac

```bash
vi ~/.bash_profile
```

Then add the following line to the bottom. 

```bash
alias gedpg='/usr/local/bin/php /your/path/to/folder/gedpg.php'
```

And reload your .bash_profiles

```bash
source ~/.bash_profile
```
