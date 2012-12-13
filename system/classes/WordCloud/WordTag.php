<?php
/**
 * This is a class that store word.
 * It also stores the frequency of appearing of that word.
 * @author kyaw2
 */

class WordTag
{
	private $word;
	private $frequency;
	
	/**
	 * Constructor. Input is word string. Initialize the word's
	 * frequency with 1.
	 * @param unknown $word
	 */
	public function __construct($word)
	{
		$this->word = $word;
		$this->frequency = 1;
	}
	
	/**
	 * increase frequency.
	 */
	public function addFrequency()
	{
		$this->frequency++;
	}
	
	/**
	 * This function is not for normal use. It is for producing
	 * desire word cloud in order to test.
	 * @param unknown $newFrequency
	 */
	public function setFrequency($newFrequency)
	{
		$this->frequency = $newFrequency;
	}
	
	public function getWord()
	{
		return $this->word;
	}
	
	public function getFrequency()
	{
		return $this->frequency;
	}
}
?>