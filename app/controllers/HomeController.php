<?php
/**
* 
*/
class HomeController extends Controller
{
	
	public function index()
	{
		$this->set('foo', 'bar');
		$this->set('baz', array('1','2',3));
	}
	
	public function helloWorld()
	{
		
	}
}