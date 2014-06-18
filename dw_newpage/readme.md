DokuWiki - Create New Page Plugin
=======

Makes the process of creating a new page in DokuWiki easier and gives us a nice form to use. A bit of a hack, but works well, follow instructions in the comments in the code files.
-----------

Regarding security, this "plugin" just automates internal DokuWiki functions, it adds no new features, therefore there are no security settings. These things must be configured within DokuWiki itself.

My suggestion is to include the form in a part of your template that is only available to logged in users, this hides the existence of the plugin (but don't rely on this alone).

You should also create user accounts in DokuWiki and only allow page creation from logged in users, this will stop spammers making pages on your Wiki by POSTing directly to the PHP script.

The afformentioned spamming is possible in any open DokuWiki with or without this plugin, so secure your site!

You have been warned :)

Enjoy,
Dushan
