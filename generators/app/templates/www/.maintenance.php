<?php

header('HTTP/1.1 503 Service Unavailable');
header('Retry-After: 300'); // 5 minutes in seconds

?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="cs"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" lang="cs"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="cs"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="cs"><!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex">
        <meta name="generator" content="Nette Framework">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>Probíhá údržba systému / Site maintenance</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <style>
            html { color: #CE3B2B; background: #FFF; font-family: 'Open Sans', serif-serif; }
            body { max-width: 700px; text-align: left; margin: 10% auto; font-size: 16px; }
            h1 { margin: 0 auto; font: 900 2em/1.3 sans-serif; margin: .3em 10% 0 10%; font-family: 'Merriweather', sans; }
            p { margin: 0 auto; font-size: 1.2em; line-height: 1.4em; margin: .4em 10%; }
            hr { height: 1px; margin: 40px 10px; border: 0 none; border-top: 1px solid #828183; }
            .en { color: #4F4744; font-size: .8em; }

            @media screen and (min-width: 500px) {
                body { font-size: 18px; }
            }
        </style>
        
    </head>

	<body>

        <section>

            <h1>Probíhá údržba systému</h1>

            <p>Omluvte prosím dočasnou nedostupnost služeb. Na stránce právě probíhá údržba. Zkuste se prosím vrátit za pár minut.</p>

        </section>
        <hr>

        <section class="en">

            <h1>We're Sorry</h1>

            <p>The site is temporarily down for maintenance. Please try again in a few minutes.</p>

        </section>
	
        <script type="text/javascript">
          WebFontConfig = {
            google: { families: [ 'Merriweather:900:latin,latin-ext', 'Open+Sans:300:latin,latin-ext' ] }
          };
          (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
              '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
          })(); 
        </script>
	</body>
</html>
<?php

exit;
