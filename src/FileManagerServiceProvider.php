<?php

namespace Kwaadpepper\ResponsiveFileManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Blade;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // Add package routes.
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        /**
         * Publish all static ressources
         */
        $FMPRIVPATH = "/../resources/filemanager/";
        $FMPUBPATH = "vendor/responsivefilemanager/";

        $FM_PUBLISH = [];

        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'config/config.php'] = config_path('rfm.php');

        $FM_SCRIPT = [
            'plugin.min.js'
        ];

        $FM_CSS =   ['jquery.fileupload-noscript.css',
                    'jquery.fileupload-ui-noscript.css',
                    'jquery.fileupload-ui.css',
                    'jquery.fileupload.css',
                    'rtl-style.css',
                    'style.css'];
        
        $FM_JS =    ['include.js','jquery.fileupload.js', 'jquery.iframe-transport.js',
                    'jquery.fileupload-angular.js','jquery.fileupload-process.js','modernizr.custom.js',
                    'jquery.fileupload-audio.js','jquery.fileupload-ui.js','plugins.js',
                    'jquery.fileupload-image.js','jquery.fileupload-validate.js','vendor/jquery.ui.widget.js',
                    'jquery.fileupload-jquery-ui.js','jquery.fileupload-video.js'];

        
        $FM_IMG =   ['clipboard_apply.png','clipboard_clear.png','copy.png','cut.png',
                    'date.png','dimension.png','down.png','download.png','duplicate.png',
                    'edit_img.png','file_edit.png','glyphicons-halflings-white.png','glyphicons-halflings.png','info.png',
                    'key.png','label.png','loading.gif','logo.png','preview.png',
                    'processing.gif','rename.png','size.png','sort.png','storing_animation.gif',
                    'trans.jpg','up.png' ,'upload.png','url.png','zip.png'];

        $FM_ICO =   ['ac3.jpg','c4d.jpg','dxf.jpg','html.jpg','mov.jpg','odp.jpg','pdf.jpg','sql.jpg','webm.jpg',
                    'accdb.jpg','css.jpg','favicon.ico','iso.jpg','mp3.jpg','ods.jpg','png.jpg','stp.jpg','wma.jpg',
                    'ade.jpg','csv.jpg','fla.jpg','jpeg.jpg','mp4.jpg','odt.jpg','ppt.jpg','svg.jpg','xhtml.jpg',
                    'adp.jpg','default.jpg','flv.jpg','jpg.jpg','mpeg.jpg','ogg.jpg','pptx.jpg','tar.jpg','xls.jpg',
                    'aiff.jpg','dmg.jpg','folder_back.png','log.jpg','mpg.jpg','otg.jpg','psd.jpg','tiff.jpg','xlsx.jpg',
                    'ai.jpg','doc.jpg','folder.png','m4a.jpg','odb.jpg','otp.jpg','rar.jpg','txt.jpg','xml.jpg',
                    'avi.jpg','docx.jpg','gif.jpg','mdb.jpg','odf.jpg','ots.jpg','rtf.jpg','vwx.jpg','zip.jpg',
                    'bmp.jpg','dwg.jpg','gz.jpg','mid.jpg','odg.jpg','ott.jpg','skp.jpg','wav.jpg'];

        $FM_ICO_DARK =  ['ac3.jpg','css.jpg','flv.jpg','jpg.jpg','mpeg.jpg','ogg.jpg','pptx.jpg','txt.jpg','zip.jpg',
                        'accdb.jpg','csv.jpg','folder_back.png','log.jpg','mpg.jpg','otg.jpg','psd.jpg','wav.jpg',
                        'ade.jpg','default.jpg','folder.png','m4a.jpg','odb.jpg','otp.jpg','rar.jpg','webm.jpg',
                        'adp.jpg','dmg.jpg','gif.jpg','mdb.jpg','odf.jpg','ots.jpg','rtf.jpg','wma.jpg',
                        'aiff.jpg','doc.jpg','gz.jpg','mid.jpg','odg.jpg','ott.jpg','sql.jpg','xhtml.jpg',
                        'ai.jpg','docx.jpg','html.jpg','mov.jpg','odp.jpg','pdf.jpg','svg.jpg','xls.jpg',
                        'avi.jpg','favicon.ico','iso.jpg','mp3.jpg','ods.jpg','png.jpg','tar.jpg','xlsx.jpg',
                        'bmp.jpg','fla.jpg','jpeg.jpg','mp4.jpg','odt.jpg','ppt.jpg','tiff.jpg','xml.jpg'];
        
        $FM_SVG = ['icon-a.svg',  'icon-b.svg',  'icon-c.svg',  'icon-d.svg',  'svg.svg'];

        $FMVENDOR_PREP = [
            '/' => $FM_SCRIPT,
            'css/' => $FM_CSS,
            'js/' => $FM_JS,
            'img/' => $FM_IMG,
            'img/ico/' => $FM_ICO,
            'img/ico_dark/' => $FM_ICO_DARK,
        ];
        $FM_VENDOR = [];

        foreach ($FMVENDOR_PREP as $folder_path => $file_table) {
            foreach ($file_table as $file) {
                $FMVENDOR[$file] = $FMPUBPATH.$folder_path.$file;
            }
        }

        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'config/config.php'] = config_path('rfm.php');

        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'/plugin.min.js'] = public_path($FMPUBPATH.'/plugin.min.js');
        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'/css'] = public_path($FMPUBPATH.'/css');
        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'/img'] = public_path($FMPUBPATH.'/img');
        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'/js'] = public_path($FMPUBPATH.'/js');
        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'/svg'] = public_path($FMPUBPATH.'/svg');
        $FM_PUBLISH[__DIR__.$FMPRIVPATH.'/lang'] = public_path($FMPUBPATH.'/lang');

        $this->publishes($FM_PUBLISH);

        /**
         * Blade print
         */

        Blade::directive('external_filemanager_path', function () use ($FMPUBPATH) {
            return $FMPUBPATH.'/';
        });

        Blade::directive('filemanager_get_resource', function ($file) use ($FMVENDOR) {
            $r = parse_url(route('FM'.$file), PHP_URL_PATH);
            if($r) return $r;
            if(isset($FMVENDOR[$file])) return $FMVENDOR[$file];
            if(config('app.debug')) throw new \Exception('unkow file '.$file.' in Reponsive File Manager');
        });

        Blade::directive('filemanager_get_config', function ($expression) {
            return config($expression);
        });
    }
}
