<?php
/**
 * Администрирование подсказок
 *
 * @author irina
 */
class WikiAdmin {
	/**
	 * Выбор подсказок для заявки
	 * @param int $requestId
	 * @return Wiki
	 */
	public static function selectForRequest ($requestId) {
		
		$officers = OfficerAdmin::getByRequest($requestId);
		$tags = OfficerAdmin::officerTag($officers);
		$requestTags = RequestAdmin::getRequestTag($requestId);
		$requestTags = CHtml::listData($requestTags, 'id', 'id');
		// $tags = array_intersect($tags, $requestTags);
		$tags = $requestTags;
		
		if (empty($tags)) {
			return array();
		}
		$criteria = new CDbCriteria();
		$criteria->join = 'JOIN tag_wiki ON t.id=tag_wiki.wiki_id AND tag_wiki.tag_id IN ('. implode(',', $tags).')';
		return Wiki::model()->findAll($criteria);
	}
}

?>
