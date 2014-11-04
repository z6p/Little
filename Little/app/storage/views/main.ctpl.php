<!DOCTYPE html>
<html>
	<head>
		<title><?php echo htmlentities($title['value']); ?></title>
	</head>
	<body>
		Hello <?php echo htmlentities($name); ?> !
		
		Memoire: <?php echo htmlentities($memory->amount); ?>
	</body>
</html>