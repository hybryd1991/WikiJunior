<?php
$voteJS = <<<DEL
	$('.rate span').click(function(){
		var vote;
		if($(this).hasClass('rateLike'))
			vote = 'like';
		if($(this).hasClass('rateDislike'))
			vote = 'disLike';

		$(this).parent().find('span').removeClass('voted');
		$(this).addClass('voted');

		if(vote){
			$.ajax({
				type: "POST",
				comment: $(this).attr('id'),
				url: "/index.php/rates/add",
				data: "id="+$(this).attr('id')+"&vote="+vote,
				success: function(data){
		 			$('[totalrateid = '+this.comment+']').html("(" + data + ")");
				}
			});
		}
	});
DEL;
Yii::app()->getClientScript()->registerScript('vote', $voteJS);
?>
<?php foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">

	<?php echo CHtml::link("#{$comment->id}", $comment->getUrl($post), array(
		'class'=>'cid',
		'title'=>'Permalink to this comment',
	)); ?>

	<div class="author">
		<?php echo $comment->authorLink; ?> says:
	</div>

	<div class="time">
		<?php echo date('F j, Y \a\t h:i a',$comment->create_time); ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->content)); ?>
	</div>

	<!-- rates -->

	<?
		$rate = new Rates;
		$vote = $rate->getVote($comment->attributes['id'], Yii::app()->user->id);
	?>

	<?php if(!Yii::app()->user->isGuest): ?>
	<div class="rate">
		<span id="<?=$comment->attributes['id']?>" class="rateLike
			<?
				if($vote == 1)
					echo 'voted';
			?>
		">
			Like
		</span> | 
		<span id="<?=$comment->attributes['id']?>" class="rateDislike
			<?
				if($vote == -1)
					echo 'voted';
			?>
		">
			Dislike
		</span>

		<span totalrateid="<?=$comment->attributes['id']?>" class="rateTotal">
			(<?=$rate->getRate($comment->attributes['id'])?>)
		</span>
	</div>
	<!-- end rate -->
	<?php endif; ?>
</div>
<!-- comment -->
<?php endforeach; ?>