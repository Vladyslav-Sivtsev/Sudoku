<?php
/*Программа для решения  Судок 
Данные отправляются через форму
Формат: каждяе позиция либо число либо '.' если поле пустое 
*/?>
<html>
<head>
<meta  http-equiv="Content-Type" content="text/html; charset="utf-8” />
</head>
<body>
<?php
if(!isset($_POST['op'])){
?>
<form action='sudoku.php' method='POST' >
<input type='text' name='dat' size=81>
<input type='submit' name='op'>
</form>
</body>
</html>
<?php	
exit();
}
//75...9..4.1...89....6.4...184.2.7...............4.1.284...9.6....51...3.1..8...45
//Заполняем массив
$dat=array();
$arrdat=array();
$len=strlen($_POST['dat']);
$str=$_POST['dat'];


for($i=0;$i<9;$i++){
	for($j=0;$j<9;$j++){
		$n=substr($_POST['dat'],((9*($i))+($j)),1);
		$dat[$i][$j]= ($n=='.')?0:$n;
	
	}
}


class sudoku{
	public $x,$y;
	function __construct($i,&$a){
		
		$this->y=(int)(($i-1)/9);
		$this->x=$i-($this->y*9)-1;;
		if($i<=81){ 
			$this->ref= new sudoku($i+1,$a);
		}else{
			$this->ref= null;
		}
			
	}
	
	
	//Выполняет основную работу
	public function settle(){
		global $dat;
		
		if($dat[$this->y][$this->x]==0){
		//Подставляем в $dat[$y][$x] возможное значение если оно не установлено
		
			for($i=1;$i<=9;$i++){
				//Если значение допустимо
				if($this->verify($i)){
					//На моей платформе был глюк $dat[$this->y][$this->x]=$i нарушал ссылочную целостность $ref!! 
					$f='i';
					$this->set($this->y,$this->x,$$f);
					
					if(($this->y==8)&&($this->x==8)){
						$this->reply();						
						break ;
					}else{
						$this->ref->settle();
					}
				}
			}
			$dat[$this->y][$this->x]=0;
			
		}else{
			if(($this->y==8)&&($this->x==8)){
				$this->reply();
			}else{
				$this->ref->settle();
			}
		}
		return;
	}

	//Проверяет допустимость значения
	public function verify($z){
		global $dat;
		//Проверяем значения по вертикали
		for($i=0;($i<9);$i++){
			if($dat[$this->y][$i]==$z){
				return false;
			}
		}
		//Проверяем значения по горизонтали
		for($j=0;($j<9);$j++){
			if($dat[$j][$this->x]==$z){
				return false;
			}
		}
		//Проверяем значение в блоке
		//Определяем координаты блока
		$lt=array((3*((int)($this->x/3))),(3*((int)($this->y/3))));
		$rb=array($lt[0]+2,$lt[1]+2);
		//Перебираем строки блока
		for($i=$lt[1];$i<=$rb[1];$i++){
			//Перибераем столбцы ячейки
			for($j=$lt[0];$j<=$rb[0];$j++){
				
				if($dat[$i][$j]==$z){
					return false;
				}
			}
		}
		return true;
	}
	
	//Кастыль
	function set($v1,$v2,$v3){
		global $dat;
		$dat[$v1][$v2]=$v3;
	}
	
	// Добавляет решение 
	function reply(){
		global $dat;
		global $arrdat;
		$arr=array();
		$str1='';
		for($i=0;$i<9;$i++){
			for($j=0;$j<9;$j++){
				$arr[$i][$j]=+$dat[$i][$j];
				$str1.=+$dat[$i][$j];
			}
			$str1.=' <br>';
		}
		$arrdat[]=$arr;
	}
	
	//Выводит результаты наглядно
	function result(){
		global $arrdat;
		$str='';
		$size=count($arrdat);
		echo 'Количество решений :'.($size).'<br><br>';
		if($size!=0){
			for($s=0;$s<$size;$s++){
				echo 'Решение №'.($s+1).'<br>';
				for($i=0;$i<9;$i++){
					for($j=0;$j<9;$j++){
						$str.=$arrdat[$s][$i][$j];
					}
					$str.='<br>';
				}
				echo $str.'<br><br>';
				$str='';
			}
		}else{
			echo 'Решений нет';
		}
	}
	
	//Для представления результата в одну строку
	function resultstr(){
		global $arrdat;
		$str='';
		$size=count($arrdat);
		echo 'Количество решений :'.($size).'<br><br>';
		if($size!=0){
			for($s=0;$s<$size;$s++){
				echo 'Решение №'.($s+1).'<br>';
				for($i=0;$i<9;$i++){
					for($j=0;$j<9;$j++){
						$str.=$arrdat[$s][$i][$j];
					}
					
				}
				echo $str.'<br><br>';
				$str='';
			}
		}else{
			echo 'Решений нет';
		}
	}
	
}
$sud= new sudoku(1,$dat);
$sud->settle();
//$sud->result();
$sud->resultstr();

 ?>
 </body>
 </html>
