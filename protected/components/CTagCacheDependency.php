<?php
/**
 * CTagCacheDependency represents a dependency based on a autoincrement(timestamp) of tags
 */
class CTagCacheDependency extends CCacheDependency
{
    protected $timestamp;
    protected $tags;
	
    public function __construct()
    {
        $this->tags = func_get_args();
    }
	
	/**
	 * Evaluates the dependency by generating and saving the data related with dependency.
	 * This method is invoked by cache before writing data into it.
	 */
	public function evaluateDependency()
	{
		$this->timestamp = time();
	}
	
	/**
	 * @return boolean whether the dependency has changed.
	 */
    public function generateDependentData()
    {
		if ($this->tags!==null)
		{
			foreach($this->tags as $tag);
			{
				$value = (int) Yii::app()->cache->get($tag);
				
				// echo $tag .'-'. $value .'-'. $this->timestamp . "\n";
				
				if ($value >= $this->timestamp)
					return true;
			}
		}
		else
			throw new CException(Yii::t('yii','CTagCacheDependency.timestamp cannot be empty.'));
    }
}