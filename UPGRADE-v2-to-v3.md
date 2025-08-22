# Upgrade guide Vdlp.Telescope plugin V2 to V3

## Requirements


### OctoberCMS 4.x
The Vdlp.Telescope plugin V3 requires October CMS 4.x or higher. Please make sure to upgrade your October CMS installation first.

### Laravel 12.x
October CMS 4.x requires Laravel 12.x or higher. Please make sure to upgrade your October CMS installation first.

## Asset publishing is no longer required
The assets for the Telescope Dashboard are no longer required to be published. The assets are now loaded
directly from the `vendor` folder.

- Remove the corresponding assets from: `your-theme-folder/assets/telescope`

