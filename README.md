# theFeed
theFeed is a news aggregation tool built on PHP. It utilises social media as a source of news, aggregating these on a DB for displaying on the browser in the desired format.

- It currently only supports Twitter as a news' source, but other social media platforms might be integrated in the future.

- theFeed has some admin functionality and enables some control over adding/removing sources and topics. It also enables removal of articles.

- theFeed is ideal for organisations which aim to improve internal communication channels by using social media, but want to offer an alternative to using personal social media accounts, as well as collating all of their official social media account feeds together for the users or guests.

- Personalisation is the focus of theFeed, letting users subscribe in and out of sources or topics, building their own timelines.

- theFeed offers basic authentication, it is advised you implement your own, or make changes.

theFeed is still under development, and a stable version is not yet ready.

Installation guide:
1.  Download or clone `repository`;
2.  Import the lastest SQL found in `install/`;
3.  Confirm that the configuration has been imported correctly with the SQL - if not, the app won't work - this is found in the `config` table;
4.  Insert a source of type `twitter` into the `sources` table,<i> this is the only supported source type</i>;
5.  Configure your `config.php`:
  5.1  Duplicate `config-dist.php` and rename it to `config.php`.
  5.2  Change the DB connection found in `CFG` class and change it to your details.
6.  Make sure your DB configuration table `config` has been properly configured.
7.  Add the required Twitter keys to `sources_config` table as on your Twitter Dev App details.
8.  Change any extra settings on the `config` table to suit your needs.
9.  [<i>Optional</i>] Upon adding sources, using the default account, subscribe to whichever sources you want to display on the homepage.
