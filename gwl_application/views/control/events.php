<?php 

	if(count($events) == 0 && $pageNumber > 1)
	{
		echo "<div class='alert alert-warning'>Nothing more to see duder!</div>";
	}
	else if(count($events) == 0)
	{
		echo "<div class='alert alert-warning'>Looks like nothing has happened yet!</div>";
	} else {
		// loop through user events
    	foreach ($events as $event)
		{
?>
			<div class="panel panel-default"> 
				<div class="panel-body media">
					
					<a class='pull-left' href='<?php echo $event->Url ?>'>
						<img class='media-object gameBoxArt eventImage' src='<?php echo $event->Image ?>' />
					</a>
					<div class="media-body clearfix eventDetail">
						<?php
							echo '<p><b>' . $event->Username . $event->Label . '</b> ' . $event->GameName . $event->PlatformsLabel . '</p>';
							echo '<p class="gameDeck">' . $event->Deck . '</p>';
							echo '<p class="datestamp pull-right">' . $event->DateStampFormatted . '</p>';
						?>
					</div>
					<?php
						// comments
						// if there are more than two comments, hide all but the most recent one
						if(count($event->comments) > 2) {
							echo '<div class="hidden" id="hiddenComments' . $event->EventID . '">';
							$numberOfHiddenComments = count($event->comments)-1;
						}

						// loop through comments
						for($i = 0; $i < count($event->comments); $i++)
						{
							$comment = $event->comments[$i];

							// if there are hidden comments (comments are hidden when there are more than 2)
							// and this is the last comment, close hidden div (always show last comment) and display link to show all comments
							if(count($event->comments) > 2 && $i == $numberOfHiddenComments)
							{
								echo '</div>';
								echo '<div id="hiddenCommentLink' . $event->EventID . '" class="eventCommentDisplay"><a class="handPointer" onclick="showComments(' . $event->EventID . ');">View ' . $numberOfHiddenComments . ' more comments</a></div>';
							}

							// display comment
							echo '
								<div class="clearfix eventCommentDisplay">
									<div class="pull-left">
										<img src="/uploads/' . $comment->ProfileImage . '" class="tinyIconImage gameBoxArt" />
									</div>
									<div class="media-body eventComment">
										<a href="/user/' . $comment->UserID . '">' . $comment->Username . '</a></b> 
										' . $comment->Comment . '
										<span class="datestamp pull-right">' . $comment->DateStampFormatted . '</span>
									</div>
								</div>';
						}

						// if logged in, show comment post box
						if($sessionUserID != null)
						{
							echo '
								<div id="newComment' . $event->EventID . '"></div>
								<div class="pull-left">
									<img src="/uploads/' . $sessionProfileImage . '" class="tinyIconImage gameBoxArt" />
								</div>
								<button type="button" class="btn btn-default pull-right" onclick="javascript:postComment(' . $event->EventID . ');">Post</button>
								<div class="media-body">
									<textarea id="commentField' . $event->EventID . '" rows="1" placeholder="Say something..." class="form-control textAreaAutoGrow" name="post"></textarea>
								</div>';
						} 
					?>
				</div>
			</div>
<?php
		}

		// user profile
		if(isset($user))
			$url = '/user/' . $user->UserID . '/';
		// game page
		else
			$url = '/game/' . $game->id . '/';

		echo '<ul class="pager">';
		if($pageNumber > 1) echo '<li class="previous"><a href="' . $url . ($pageNumber-1) . '">&larr; Newer</a></li>';
		echo '<li class="next"><a href="' . $url . ($pageNumber+1) . '">Older &rarr;</a></li></ul>';
	}
?>