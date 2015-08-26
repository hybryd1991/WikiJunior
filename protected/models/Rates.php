<?php

/**
 * This is the model class for table "_tbl_rates".
 *
 * The followings are the available columns in table '_tbl_rates':
 * @property integer $id
 * @property integer $user_id
 * @property integer $comment_id
 * @property integer $rate
 */
class Rates extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_rates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, comment_id, rate', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, comment_id, rate', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		/**/
		return array(
			'comment' => array(self::BELONGS_TO, 'Comment', 'comment_id'),
			'author' => array(self::BELONGS_TO, 'Users', 'user_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'comment_id' => 'Comment',
			'rate' => 'Rate',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('rate',$this->rate);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rates the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//adding new vote to comment
	function addRate($comment, $user, $vote){
		if($vote === 'like')
			$vote = 1;
		if($vote === 'disLike')
			$vote = -1;

		$sql = Yii::app()->db->createCommand();
		$sql->delete('tbl_rates', 'user_id=:user and comment_id=:comment', array(':user'=>$user, ':comment'=>$comment));

		$sql->reset();

		$sql->insert('tbl_rates', array(
			'id'=>NULL,
		    'user_id'=>$user,
		    'comment_id'=>$comment,
		    'rate'=>$vote
		));
		return TRUE;
	}

	//get current vote
	function getVote($comment, $user){
		$selection = Yii::app()->db->createCommand()
		->select('rate')
		->from('tbl_rates')
		->where('comment_id=:comment and user_id=:user', array(':comment'=>$comment, ':user'=>$user))
		->queryRow();
		if($selection){
			return $selection['rate'];
		}else{
			return FALSE;
		}
	}

	function getRate($comment){
		$sum = Yii::app()->db->createCommand()
		->select('SUM(rate)')
		->from('tbl_rates')
		->where('comment_id=:comment', array(':comment'=>$comment))
		->queryRow();

		return $sum['SUM(rate)'];
	}
}
