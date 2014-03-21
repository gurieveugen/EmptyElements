<?php

class EmptyElements{
	//                          __              __      
	//   _________  ____  _____/ /_____ _____  / /______
	//  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
	// / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
	// \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
	const LEVEL1         = 0;
	const LEVEL2         = 1;
	const LEVEL3         = 2;
	const LEVEL4         = 3;
	const DEFAULT_COUNT  = 100000;
	const DECORATE_CLASS = 'bg-primary';
	//                __  _                 
	//   ____  ____  / /_(_)___  ____  _____
	//  / __ \/ __ \/ __/ / __ \/ __ \/ ___/
	// / /_/ / /_/ / /_/ / /_/ / / / (__  ) 
	// \____/ .___/\__/_/\____/_/ /_/____/  
	//     /_/                              
	private $table;
	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{
		ini_set('memory_limit', '250M');	

		$this->table[self::LEVEL1]['title'] = 'Вариант с обыкновенным перебом';
		$this->table[self::LEVEL2]['title'] = 'Вариант с array_diff';
		$this->table[self::LEVEL3]['title'] = 'Вариант с array_filter';
		$this->table[self::LEVEL4]['title'] = 'Вариант с array_keys';

		// =========================================================
		// Level [1]
		// =========================================================		
		$arr_level1 = $this->generateArray();
		$this->table[self::LEVEL1]['start_time'] = microtime(true);		
		$arr_level1 = $this->deleteEmptyLevel1($arr_level1);		
		$this->table[self::LEVEL1]['end_time'] = microtime(true);		
		unset($arr_level1);

		// =========================================================
		// Level [2]
		// =========================================================		
		$arr_level2 = $this->generateArray();
		$this->table[self::LEVEL2]['start_time'] = microtime(true);		
		$arr_level2 = $this->deleteEmptyLevel2($arr_level2);		
		$this->table[self::LEVEL2]['end_time'] = microtime(true);		
		unset($arr_level2);

		// =========================================================
		// Level [3]
		// =========================================================		
		$arr_level3 = $this->generateArray();
		$this->table[self::LEVEL3]['start_time'] = microtime(true);		
		$arr_level3 = $this->deleteEmptyLevel3($arr_level3);		
		$this->table[self::LEVEL3]['end_time'] = microtime(true);		
		unset($arr_level3);

		// =========================================================
		// Level [4]
		// =========================================================		
		$arr_level4 = $this->generateArray();
		$this->table[self::LEVEL4]['start_time'] = microtime(true);		
		$arr_level4 = $this->deleteEmptyLevel4($arr_level4);		
		$this->table[self::LEVEL4]['end_time'] = microtime(true);		
		unset($arr_level4);
	}

	/**
	 * Count Elements
	 * @param  integer $count 
	 * @return array
	 */
	public function generateArray($count = self::DEFAULT_COUNT)
	{
		$limit = $count/10;
		$array = range(0, ($count-1));
	    for($i = 0; $i < $limit-1; $i++)
	    {	
	    	$this->setEmpty($array, $count-1);
	    }
	    return $array;
	}

	/**
	 * Set one empty element
	 * @param array $arr
	 * @param integer $count 
	 * @return boolean
	 */
	public function setEmpty(&$arr, $count)
	{
		$offset = mt_rand(0, $count);		
		if($arr[$offset] != '') 
		{
			$arr[$offset] = '';
			return true;
		}
		$this->setEmpty($arr, $count);
	}

	/**
	 * Level [1]
	 * @param  array $arr 
	 * @return array      
	 */
	public function deleteEmptyLevel1($arr)
	{
		foreach ($arr as $key => &$value) 
		{
			if($value == "") unset($arr[$key]);
		}
		return $arr;
	}	

	/**
	 * Level [2]
	 * @param  array $arr 
	 * @return array      
	 */
	public function deleteEmptyLevel2($arr)
	{
		$empty_elements = array("");
		return array_diff($arr, $empty_elements);
	}	

	/**
	 * Level [3]
	 * @param  array $arr 
	 * @return array      
	 */
	public function deleteEmptyLevel3($arr)
	{
		return array_filter($arr);
	}

	/**
	 * Level [4]
	 * @param  array $arr 
	 * @return array      
	 */
	public function deleteEmptyLevel4($arr)
	{		
		$empty_elements = array_keys($arr, '');		
		foreach ($empty_elements as &$e) unset($arr[$e]);

		return $arr;
	}

	/**
	 * Decorate the most fastest method	 
	 * @return integer
	 */
	public function getFastest()
	{
		$x = 0;
		if($this->table)
		{
			foreach ($this->table as $key => $value) 
			{
				$new_arr[$key] = $value['end_time'] - $value['start_time'];
			}
			asort($new_arr);
			reset($new_arr);
			$x = key($new_arr);
		}
		return $x;
	}		

	/**
	 * Get table
	 * @return array
	 */
	public function getTable()
	{
		return $this->table;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Фильтруем пустые элементы</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/docs.css">
</head>
<body>
	<?php 
		$empty_elements = new EmptyElements(); 
		$table          = $empty_elements->getTable();
		$fastest        = $empty_elements->getFastest();
	?>
	<div class="row">
		<div class="col-md-4 col-lg-4">
			<div class="logo-block">
				<img alt="gurievcreative" src="img/mylogo.png">
				<h1>Guriev Creative</h1>				
				<span>МИР МЕНЯЮТ СМЕЛЫЕ</span>
			</div>
		</div>
		<div class="col-md-8 col-lg-8">
			<div class="bs-callout bs-callout-info">
				<h4>Удаление пустых эллементов в массиве</h4>
				<p>Очень часто пых - пых программистам приходится очищать массив от лишних элементов перед выводом куда-то.  Я решил провести небольшой эксперимент с разными подходами решения данной проблемы и определить какой из них самый быстрый.</p>
			</div>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Title</th>
						<th>Start time</th>
						<th>End time</th>
						<th>Elapsed time</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($table as $key => $value) 
						{
							$fastest_class = ($key == $fastest) ? 'class="'.EmptyElements::DECORATE_CLASS.'"' : '';							
							?>
							<tr <?php echo $fastest_class; ?>>
								<td><b><?php echo $value['title']; ?></b></td>
								<td><?php echo $value['start_time']; ?></td>
								<td><?php echo $value['end_time']; ?></td>
								<td><?php printf('Скрипт выполнялся %.9F сек.', $value['end_time'] - $value['start_time']); ?></td>
							</tr>
							<?php
						}
					?>	
				</tbody>
			</table>	
		</div>
	</div>
	
</body>
</html>

