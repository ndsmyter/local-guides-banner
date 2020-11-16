/*
 * statistics.sql
 *
 * @author Nicolas De Smyter <nicolasdesmyter@gmail.com>
 * @package local-guides-banner
 * @copyright 2019 Nicolas De Smyter
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU GENERAL PUBLIC LICENSE Version 3
 * @version 1.0.0
 * @link https://ndsmyter.be
 * @since 14/07/20, 11:14
 */

SELECT MIN(create_time) min,
	MAX(create_time) max,
    COUNT(*) totaal,
	datediff(MAX(create_time),MIN(create_time)) dagen,
    COUNT(*)/ datediff(MAX(create_time),MIN(create_time)) per_dag,
    COUNT(*)/ datediff(MAX(create_time),MIN(create_time))/24 per_uur,
    COUNT(*)/ datediff(MAX(create_time),MIN(create_time))/24/60 per_minuut FROM `localguidesbanner`
