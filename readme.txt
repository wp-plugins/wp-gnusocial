=== WP-GNU social ===
Contributors: nat23, deugarte, Rodma, mayra-rodriguez, carolitar, voylinux, elektrolupo
Tags: GNU social, federated web, conversations, social web, statusnet, comments, commenting
Requires at least: 3.2
Tested up to: 4.2.1
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

GNU social based comment system for WordPress

== Description ==

[GNU social](https://gnu.io/social/) based comment system. After installing and configurating the plugin, evey new post will start a new conversation in your GNU social node. The conversation will be displayed and locally saved as usual wordpress comments in your blog. Everybody with an user in a node with someone subscribed to your GNU social user (i.e. your node) can comment your post directly from its own GNU social node.

== Installation ==

You can either use the built in WordPress installer or install the plugin manually.

For an automated installation:
1. Go to 'Plugins -> Add New' on your WordPress Admin page.
2. Search for the 'WP-GNU social' plugin.
3. Install by clicking the 'Install Now' button.
4. Activate the plugin on the 'Plugins' page in your WordPress Admin.

For a manual installation:
1. Upload 'wp-gnusocial' folder to the '/wp-content/plugins/' directory of your WordPress installation.
2. Activate the plugin on the 'Plugins' page in your WordPress Admin.

= Getting started =

1. After installing and activating the plugin, be sure to visit the plugin's settings page at 'Settings -> WP-GNU social' on your WordPress Admin page.
2. Simply add the API url of your GNU social node, your username and password.
3. Publish a new post to get started.

= What's GNU social? =
GNU social is a free social distributed networking platform. It helps people in a community, company or group to exchange short status updates, do polls, announce events, or other social activities. More about it on https://gnu.io/social/

= This plugin speaks your language =
* Esperanto
* Spanish
* English
* German -- contributed by [Frosch](http://blog.atari-frosch.de/)

Your language isn't listed? Then feel free to and help make this plugin more accessible!

= Credits =
* Plugin's logo: [Moshpirit](https://quitter.es/moshpirit)


== Screenshots ==

1. Configuration page
2. Comments in GNU social
3. Comments in your blog

== Changelog ==

= 0.3 =
* Optimizar la configuración del plugin. Pedir solamente el identificador de GNU social y la contraseña

= 0.2 =
* Los comentarios muestran el nombre elegido por el usuario y no el nickname
* Url en el primer comentario usando post_name
* Control a la hora de actulizar el listado de comentarios asociados a un post para evitar comentarios repetidos
* Reimplementación de la gestión de avatares
* Uso de WordPress HTTP class para no depender de curl
* Añadida notificación trás la activación del plugin para informar y enlazar a su configuración

= 0.1 =
* Just getting started...
