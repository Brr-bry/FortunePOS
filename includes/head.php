
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel='icon' href='./icons/logo.png'>
    <title>
        <?php 
            if(isset($title)){
                echo $title;
            }else{
                echo "FortunePOS";
            }
        ?>
    </title>
</head>