SELECT MIN(create_time) min,
	MAX(create_time) max,
    COUNT(*) totaal,
	datediff(MAX(create_time),MIN(create_time)) dagen,
    COUNT(*)/ datediff(MAX(create_time),MIN(create_time)) per_dag,
    COUNT(*)/ datediff(MAX(create_time),MIN(create_time))/24 per_uur,
    COUNT(*)/ datediff(MAX(create_time),MIN(create_time))/24/60 per_minuut FROM `localguidesbanner`