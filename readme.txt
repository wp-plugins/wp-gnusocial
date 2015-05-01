=== WP-GNU social ===
Contributors: elektrolupo
Tags: GNU social, federated web, conversations, social web, statusnet
Requires at least: 3.2
Tested up to: 4.2.1
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An GNU social based comment system for WordPress

== Description ==

An [GNU social](https://gnu.io/social/) based comment system. After installing and configurating the plugin, evey new post will start a new conversation in your GNU social node. The conversation will be displayed and locally saved as usual wordpress comments in your blog. Everybody with an user in a node with someone subscribed to your GNU social user (i.e. your node) can comment your post directly from its own GNU social node.

This plugin will work only for new posts. So, in order to see this plugin working, after activating and configuring it, you have to publish a new post.

== Installation ==

1. Upload the 'wp-gnusocial' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit 'Settings > WP-GNU social' and adjust your configuration.

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
