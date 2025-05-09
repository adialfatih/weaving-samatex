<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Samatex Data">
    <meta name="apple-mobile-web-app-title" content="Samatex Data">
    <meta name="theme-color" content="#246cd1">
    <meta name="msapplication-navbutton-color" content="#246cd1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="https://direksi.rindangjati.com/all-dashboard">
    <link rel="apple-touch-icon" sizes="180x180" href="https://sm.rindangjati.com/logo/logo.png"/>
	<link rel="icon" type="image/png" sizes="32x32" href="https://sm.rindangjati.com/logo/logo.png"/>
	<link rel="icon" type="image/png" sizes="16x16" href="https://sm.rindangjati.com/logo/logo.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager</title>
    <link rel="stylesheet" href="<?=base_url();?>/new_db/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Vithkuqi&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css">
    <style>
        .autoComplete_wrapper input{
            width: 95%;
            transform: translateX(10px);
        }
    </style>
</head>
<body>
    <form action="<?=base_url('alldashboard/proseslogin');?>" method="post">
        <div class="boxlogin">
            <div class="rowcol">
                <img src="<?=base_url();?>logo/logo.png" alt="Logo Aplikasi Samatex">
            </div>
            <div class="rowcol">
                <h1>Login Dashboard</h1>
            </div>
            <div class="rowcol">
                <div class="iptcontrol mt20">
                    <label for="username">Username</label>
                    <input type="text" placeholder="Masukan username" name="username" required>
                </div>
            </div>
            <div class="rowcol">
                <div class="iptcontrol mt10">
                    <label for="password">Password</label>
                    <input type="password" placeholder="Masukan password" name="password" required>
                </div>
            </div>
            <div class="rowcol mt15">
                <button type="submit">Login</button>
            </div>
        </div>
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register("serviceworker.js");
            }
    </script>
</body>
</html>