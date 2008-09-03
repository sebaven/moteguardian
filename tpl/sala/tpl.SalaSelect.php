<?
/*
Para generar los insert into se puede utilizar la siguiente expresión regular:
	FIND: ^.*&idSala=([0-9]+)" title="<?= $prefijoTitle ?>([A-Za-z0-9 áéíóúÁÉÍÓÚ]+)"/>
	REPLACE: INSERT INTO `sala` (`id`, `descripcion`) VALUES (\1, '\2');

*/
?>
<img src="imagenes/museo/plano.jpg" width="1109" height="724" border="0" usemap="#plano"/>
<map name="plano" id="plano">
    <area shape="poly" coords="603,500,480,416,520,394,643,476" href="<?= $redir ?>&idSala=1" title="<?= $prefijoTitle ?>Sala Mayor I"/>
    <area shape="poly" coords="669,607,701,591,726,608,696,624" href="<?= $redir ?>&idSala=2" title="<?= $prefijoTitle ?>Sala Lateral Principal I"/>
	<area shape="poly" coords="628,118,722,84,771,137,683,171"  href="<?= $redir ?>&idSala=3" title="<?= $prefijoTitle ?>Exposiciones Temporales"/>
	<area shape="poly" coords="867,102,811,52,728,79,774,129"   href="<?= $redir ?>&idSala=4" title="<?= $prefijoTitle ?>Sala Mayor II"/>
    <area shape="circle" coords="664,518,24" href="<?= $redir ?>&idSala=5" title="<?= $prefijoTitle ?>Sala Óvalo"/>
    <area shape="poly" coords="559,537,592,518,621,537,587,555" href="<?= $redir ?>&idSala=6" title="<?= $prefijoTitle ?>Sala Lateral Principal I"/>
    <area shape="poly" coords="697,587,626,541,594,557,663,602" href="<?= $redir ?>&idSala=7" title="<?= $prefijoTitle ?>Sala Principal"/>
    <area shape="poly" coords="713,519,653,544,697,569,745,546" href="<?= $redir ?>&idSala=8" title="<?= $prefijoTitle ?>Sala de Conciertos"/>
    <area shape="poly" coords="790,581,756,597,716,568,748,555" href="<?= $redir ?>&idSala=9" title="<?= $prefijoTitle ?>Sala Murillo"/>
    <area shape="poly" coords="799,515,761,534,816,566,846,552" href="<?= $redir ?>&idSala=10" title="<?= $prefijoTitle ?>Sala del Mirador"/>
    <area shape="poly" coords="737,511,771,495,791,509,758,527" href="<?= $redir ?>&idSala=11" title="<?= $prefijoTitle ?>Sala Reliquias"/>
    <area shape="poly" coords="719,461,684,481,729,507,765,493" href="<?= $redir ?>&idSala=12" title="<?= $prefijoTitle ?>Sala Barroco"/>
    <area shape="poly" coords="516,385,549,369,571,383,537,400" href="<?= $redir ?>&idSala=13" title="<?= $prefijoTitle ?>Diapoteca"/>
    <area shape="poly" coords="547,403,574,388,595,399,566,414" href="<?= $redir ?>&idSala=14" title="<?= $prefijoTitle ?>Sala de Invidente"/>
    <area shape="poly" coords="566,419,601,405,644,431,611,448" href="<?= $redir ?>&idSala=15" title="<?= $prefijoTitle ?>Sala Homenaje"/>
    <area shape="poly" coords="619,453,646,437,663,449,638,464" href="<?= $redir ?>&idSala=16" title="<?= $prefijoTitle ?>Sala Peremne"/>
    <area shape="poly" coords="644,467,673,452,690,465,655,482" href="<?= $redir ?>&idSala=17" title="<?= $prefijoTitle ?>Sala de Recuerdos"/>
    <area shape="poly" coords="679,448,709,432,728,443,697,459" href="<?= $redir ?>&idSala=18" title="<?= $prefijoTitle ?>Sala de Reunión"/>
    <area shape="poly" coords="717,431,748,415,771,426,737,441" href="<?= $redir ?>&idSala=19" title="<?= $prefijoTitle ?>Sala Rincón"/>
    <area shape="poly" coords="693,415,723,398,746,410,713,428" href="<?= $redir ?>&idSala=20" title="<?= $prefijoTitle ?>Sala General"/>
    <area shape="poly" coords="688,410,641,381,673,366,722,394" href="<?= $redir ?>&idSala=21" title="<?= $prefijoTitle ?>Sala Acústica"/>
    <area shape="poly" coords="681,416,651,430,674,444,703,430" href="<?= $redir ?>&idSala=22" title="<?= $prefijoTitle ?>Sala Capitular"/>
    <area shape="poly" coords="681,414,633,382,602,401,650,428" href="<?= $redir ?>&idSala=23" title="<?= $prefijoTitle ?>Sala de Eventos"/>
    <area shape="poly" coords="583,381,610,367,624,380,598,393" href="<?= $redir ?>&idSala=24" title="<?= $prefijoTitle ?>Sala Media"/>
    <area shape="poly" coords="616,365,653,347,673,360,637,378" href="<?= $redir ?>&idSala=25" title="<?= $prefijoTitle ?>Sala de Simposios"/>
    <area shape="poly" coords="558,364,577,353,600,366,575,377" href="<?= $redir ?>&idSala=26" title="<?= $prefijoTitle ?>Sala Vidriada"/>
    <area shape="poly" coords="381,397,411,384,441,398,410,414" href="<?= $redir ?>&idSala=27" title="<?= $prefijoTitle ?>Sala Velázquez"/>
    <area shape="poly" coords="421,375,458,356,489,378,454,397" href="<?= $redir ?>&idSala=28" title="<?= $prefijoTitle ?>Sala Panorámica"/>
    <area shape="poly" coords="466,348,545,306,591,324,508,376" href="<?= $redir ?>&idSala=29" title="<?= $prefijoTitle ?>Sala de las Musas"/>
    <area shape="poly" coords="538,296,604,336,647,318,583,273" href="<?= $redir ?>&idSala=30" title="<?= $prefijoTitle ?>Patio"/>
    <area shape="poly" coords="316,303,395,357,429,338,352,287" href="<?= $redir ?>&idSala=31" title="<?= $prefijoTitle ?>Corredor C1"/>
    <area shape="poly" coords="361,281,398,309,429,290,390,266" href="<?= $redir ?>&idSala=32" title="<?= $prefijoTitle ?>Corredor C2"/>
    <area shape="poly" coords="399,264,433,247,468,272,441,288" href="<?= $redir ?>&idSala=33" title="<?= $prefijoTitle ?>Corredor C3"/>
    <area shape="poly" coords="438,244,468,226,507,253,478,269" href="<?= $redir ?>&idSala=34" title="<?= $prefijoTitle ?>Corredor C4"/>
    <area shape="poly" coords="513,256,479,275,503,287,531,270" href="<?= $redir ?>&idSala=35" title="<?= $prefijoTitle ?>Hemeroteca"/>
    <area shape="poly" coords="443,292,471,276,491,289,463,306" href="<?= $redir ?>&idSala=36" title="<?= $prefijoTitle ?>Videoteca"/>
    <area shape="poly" coords="404,310,435,293,452,306,424,323" href="<?= $redir ?>&idSala=37" title="<?= $prefijoTitle ?>Biblioteca"/>
    <area shape="poly" coords="429,327,449,342,481,327,459,313" href="<?= $redir ?>&idSala=38" title="<?= $prefijoTitle ?>Sala Bellas Artes"/>
    <area shape="poly" coords="467,307,495,294,519,306,493,323" href="<?= $redir ?>&idSala=39" title="<?= $prefijoTitle ?>Fonoteca"/>
    <area shape="poly" coords="508,291,540,269,560,278,523,300" href="<?= $redir ?>&idSala=40" title="<?= $prefijoTitle ?>Mapoteca"/>
    <area shape="poly" coords="498,183,516,196,536,186,517,172" href="<?= $redir ?>&idSala=41" title="<?= $prefijoTitle ?>Sala Jerónimos"/>
    <area shape="poly" coords="612,138,641,165,656,155,638,137" href="<?= $redir ?>&idSala=42" title="<?= $prefijoTitle ?>Escalera A"/>
    <area shape="poly" coords="836,120,862,144,896,132,875,108" href="<?= $redir ?>&idSala=43" title="<?= $prefijoTitle ?>Sala Mini I"/>
    <area shape="poly" coords="808,130,831,154,859,146,832,123" href="<?= $redir ?>&idSala=44" title="<?= $prefijoTitle ?>Sala Colonial"/>
    <area shape="poly" coords="771,144,802,129,831,159,795,171" href="<?= $redir ?>&idSala=45" title="<?= $prefijoTitle ?>Sala Lápidas"/>
    <area shape="poly" coords="637,214,704,192,712,207,651,226" href="<?= $redir ?>&idSala=46" title="<?= $prefijoTitle ?>Depósito D2"/>
    <area shape="poly" coords="647,166,661,159,675,169,655,179" href="<?= $redir ?>&idSala=47" title="<?= $prefijoTitle ?>Depósito D1"/>
    <area shape="poly" coords="707,190,744,175,754,186,715,201" href="<?= $redir ?>&idSala=48" title="<?= $prefijoTitle ?>Depósito D3"/>
    <area shape="poly" coords="481,228,549,195,581,212,511,249" href="<?= $redir ?>&idSala=49" title="<?= $prefijoTitle ?>Hall de Entrada"/>
    <area shape="poly" coords="591,272,660,243,727,299,664,328" href="<?= $redir ?>&idSala=50" title="<?= $prefijoTitle ?>Sala de Exposiciones"/>
    <area shape="poly" coords="514,163,606,222,669,194,591,133" href="<?= $redir ?>&idSala=51" title="<?= $prefijoTitle ?>Auditorio"/>
    <area shape="poly" coords="516,249,583,216,636,251,562,278" href="<?= $redir ?>&idSala=52" title="<?= $prefijoTitle ?>Vestíbulo Principal"/>
    <area shape="poly" coords="653,342,744,403,797,365,726,300" href="<?= $redir ?>&idSala=53" title="<?= $prefijoTitle ?>Sala del Botánico"/>
    <area shape="poly" coords="593,347,650,324,666,333,616,359" href="<?= $redir ?>&idSala=54" title="<?= $prefijoTitle ?>Pasillo P1"/>
    <area shape="poly" coords="668,180,679,189,746,171,759,184,792,172,768,143" href="<?= $redir ?>&idSala=55" title="<?= $prefijoTitle ?>Hall Lateral"/>
</map>
