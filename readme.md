# Text to Speech with Speechify

* Contributors:      iamarunchaitanyajami
* Tags:              Speechify, text to speech, Podcast
* Stable tag:        1.0.1
* Requires at least: 5.9
* Tested up to:      6.7
* Requires PHP:      8.0
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate high quality speech with Speechify API.

## Requirements
- Requires PHP: 8.0 & greater

## Contributors

- Contributors: iamarunchaitanyajami

## Description

Generate high quality speech with our state-of-the-art Speechify artificial intelligence models in just single click.

## Plugin Installation

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/acjpd-text-to-speech-speechify` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

## == Changelog ==

### 1.0.2
* Fixed mismatched_plugin_name

### 1.0.1
* Text domain changes.
* Plugin slug changes.
* Fix UI issues on block editor.

### 1.0.0
* Converts text to speech for selected post type.
* Settings page to control the conversion flow.
* Works with gutenberg post type only.
* Preserve all podcast in a separate post type hidden to public.

## == Upgrade Notice ==

### 1.0.2
* Fixed mismatched_plugin_name

### 1.0.1
* Text domain changes.
* Plugin slug changes.
* Fix UI issues on block editor.

### 1.0.0
* Converts text to speech for selected post type.
* Settings page to control the conversion flow.
* Works with gutenberg post type only.
* Preserve all podcast in a separate post type hidden to public.

## == Frequently Asked Questions ==

* Where do we get the API key?
    * Please login to the speechify console https://console.sws.speechify.com/api-keys and generate the key
* Will this plugin works for WordPress Multisite?
    * Yes
* Will this plugin works for WordPress Single Site?
    * Yes
* Can we extend this to multiple post types ?
  * Yes, go to settings ``wp-admin/admin.php?page=acjpd-text-to-speech-speechify-page&tab=wp-settings`` and select the required post type.
* What all language types is supported in this plugin.
  * English (en)
  * French (fr-FR)
  * German (de-DE)
  * Spanish (es-ES)
  * Portuguese (pt-BR)
  * Portuguese (pt-PT)
* What all language types is supported in this plugin.
  * The format for the output audio. Note, that the current default is "wav".
  * This can be changes in settings page.
  * Other formats:
    * wav
    * mp3
    * ogg
    * aac
* What all Voice types is supported in this plugin.
  * We support all voices give by speechify. It depends on API subscription.

## == Screenshots ==

1. Go to `Text to Speech with Speechify` Settings Screen.
2. Add speechify api-key in Credentials tab.  
3. Select the post type you want this feature to be available.