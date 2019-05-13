<?php

/**
 * Test
 */
class Test
{
	
	public function hello()
	{
		return "hello world";
	}
	public function sayWhat($msg='',$m2='')
	{
		return $msg[0].$m2.$msg[1];
	}
	
}
