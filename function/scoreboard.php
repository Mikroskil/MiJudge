<?php

function genScoreBoard($connect)
{
	$cid = $_GET['contest'];
	
	$res = newQuery($connect, "SELECT login AS ARRAYKEY, login, team.name,
	                 team.categoryid, team.affilid, sortorder,
	                 country, color, team_affiliation.name AS affilname
	                 FROM team
	                 LEFT JOIN team_category
	                        ON (team_category.categoryid = team.categoryid)
	                 LEFT JOIN team_affiliation
	                        ON (team_affiliation.affilid = team.affilid)
	                 WHERE enabled = 1 AND visible = 1");
	$teams = $res->fetchAll();

	$res = newQuery($connect, "SELECT probid AS ARRAYKEY,
	                 probid, name, color, LENGTH(problemtext) AS hastext FROM problem
	                 WHERE cid = :cid AND allow_submit = 1
	                 ORDER BY probid", array('cid' => $cid));
	$probs = $res->fetchAll();
	
	$res = newQuery($connect, "SELECT categoryid AS ARRAYKEY,
 	                  categoryid, name, color FROM team_category WHERE visible = 1 ORDER BY sortorder,name,categoryid");
	$categs = $res->fetchAll();
	
	$MATRIX = $SCORES = array();
	$SUMMARY = array('num_correct' => 0,
	                 'affils' => array(), 'countries' => array(),
					 'problems' => array());
	
	$arrayTeamLogin = array();
	$arrayProblemId = array();
	
	$scoredata = newQuery($connect, "select ct.cid, a.login as teamid, a.name, b.probid, 
		(case when d.minCorrect is null then (case when c.teamid is null then 0 else c.totalSubmission end) else d.minCorrect end) as submissions, 
		0 as pending,
		(case when d.timeCorrect is null then 0 else (case when ct.starttime < d.timeCorrect then TIMESTAMPDIFF(MINUTE, ct.starttime, d.timeCorrect) else 0 end) end) as totaltime, 
		(case when d.timeCorrect is null then false else true end) is_correct from team a
		inner join problem b on b.cid=:cid
		inner join contest ct on b.cid=ct.cid
		left join (
			select teamid, probid, count(*) as totalSubmission from submission a
			inner join judging b on a.submitid=b.submitid and b.valid=1 where a.cid=:cid1 and b.result is not null group by teamid, probid
		) c on a.login=c.teamid and b.probid=c.probid
		left join (
			select a.teamid, a.probid, MAX(submittime) timeCorrect, count(*) as minCorrect from submission a
			inner join (
				select a.submitid, a.teamid, a.probid, b.result
				from submission a
				inner join judging b on a.submitid=b.submitid
				where result='correct'
				group by teamid, probid
			) b on a.submitid <=b.submitid and a.teamid=b.teamid and a.probid=b.probid
			where cid=:cid2
			group by teamid, probid
		) d on a.login=d.teamid and b.probid=d.probid", array('cid' => $cid, 'cid1' => $cid, 'cid2' => $cid));
	
	foreach ($teams as $login => $team ) {
		$SCORES[$team['login']]['num_correct'] = 0;
		$SCORES[$team['login']]['total_time']  = 0;
		$SCORES[$team['login']]['solve_times'] = array();
		$SCORES[$team['login']]['rank']        = 0;
		$SCORES[$team['login']]['teamname']    = $team['name'];
		$SCORES[$team['login']]['categoryid']  = $team['categoryid'];
		$SCORES[$team['login']]['sortorder']   = $team['sortorder'];
		$SCORES[$team['login']]['affilid']     = $team['affilid'];
		$SCORES[$team['login']]['country']     = $team['country'];
		array_push($arrayTeamLogin, $team['login']);
	}
	
	foreach( $probs as $prob => $value) {
		if ( !isset($SUMMARY['problems'][$prob]) ) {
			$SUMMARY['problems'][$value['probid']]['num_submissions'] = 0;
			$SUMMARY['problems'][$value['probid']]['num_pending'] = 0;
			$SUMMARY['problems'][$value['probid']]['num_correct'] = 0;
			$SUMMARY['problems'][$value['probid']]['best_time'] = NULL;
			$SUMMARY['problems'][$value['probid']]['best_time_sort'] = array();
		}
		array_push($arrayProblemId, $value['probid']);
	}

	while ( $srow = $scoredata->fetch() ) {
		if ( ! in_array ( $srow['teamid'], $arrayTeamLogin ) ||
		     ! in_array ( $srow['probid'], $arrayProblemId ) ) continue;

		$penalty = getPenaltyTime( $srow['is_correct'], $srow['submissions'] );

		$MATRIX[$srow['teamid']][$srow['probid']] = array (
			'is_correct'      => (bool) $srow['is_correct'],
			'num_submissions' => $srow['submissions'],
			'num_pending'     => $srow['pending'],
			'time'            => $srow['totaltime'],
			'penalty'         => $penalty );

		if ( $srow['is_correct'] ) {
			$SCORES[$srow['teamid']]['num_correct']++;
			$SCORES[$srow['teamid']]['solve_times'][] = $srow['totaltime'];
			$SCORES[$srow['teamid']]['total_time'] += $srow['totaltime'] + $penalty;
		}
	}

	uasort($SCORES, 'cmp');

	$prevsortorder = -1;
	foreach( $SCORES as $team => $totals ) {

		if ( $totals['sortorder'] != $prevsortorder ) {
			$prevsortorder = $totals['sortorder'];
			$rank = 0;
			$prevteam = null;
		}
		$rank++;
		if ( isset($prevteam) && cmpscore($SCORES[$prevteam], $totals)==0 ) {
			$SCORES[$team]['rank'] = $SCORES[$prevteam]['rank'];
		} else {
			$SCORES[$team]['rank'] = $rank;
		}
		$prevteam = $team;

		$SUMMARY['num_correct'] += $totals['num_correct'];
		foreach( $probs as $prob => $value) {
			if ( ! isset ( $MATRIX[$team][$value['probid']] ) ) {
				$MATRIX[$team][$value['probid']] = array('num_submissions' => 0, 'num_pending' => 0,
				                              'is_correct' => 0, 'time' => 0, 'penalty' => 0);
			}
			$pdata = $MATRIX[$team][$value['probid']];
			$psum = &$SUMMARY['problems'][$value['probid']];

			@$psum['num_submissions'] += $pdata['num_submissions'];
			@$psum['num_pending'] += $pdata['num_pending'];
			@$psum['num_correct'] += ($pdata['is_correct'] ? 1 : 0);

			if ( $pdata['is_correct'] ) {
				if ( !isset($psum['best_time_sort'][$totals['sortorder']]) ||
				     $pdata['time']<$psum['best_time_sort'][$totals['sortorder']] ) {
					@$psum['best_time_sort'][$totals['sortorder']] = $pdata['time'];
				}

				if ( !isset($psum['best_time']) ||
				     $pdata['time'] < @$psum['best_time'] ) {
					@$psum['best_time'] = $pdata['time'];
				}
			}
		}
	}

	return array( 'matrix'     => $MATRIX,
	              'scores'     => $SCORES,
	              'summary'    => $SUMMARY,
	              'teams'      => $teams,
	              'problems'   => $probs,
	              'categories' => $categs );
}

function renderScoreBoardTable($sdata)
{
	$cid = $_GET['contest'];
	$static = true;
	$showlegends = false;
	$displayrank = true;

	$scores  = $sdata['scores'];
	$matrix  = $sdata['matrix'];
	$summary = $sdata['summary'];
	$teams   = $sdata['teams'];
	$probs   = $sdata['problems'];
	$categs  = $sdata['categories'];
	unset($sdata);

	$SHOW_AFFILIATIONS = 0;
	$SHOW_PENDING      = 0;

	echo "
		<table class=\"table bordered hovered\">
			<thead>
				<tr>
					<th class=\"text-center\">#</th>
					<th class=\"text-left\">User</th>
					<th class=\"text-center\">Solved(Penalty)</th>";
	foreach( $probs as $pr ) {
		echo "
					<th title=\"problem '" . htmlspecialchars($pr['name']) . "'\">
						<a href=\"?contest=$_GET[contest]&problem=" . htmlspecialchars($pr['probid']) . "\">" . htmlspecialchars($pr['probid']) . "</a>
					</th>";
	}
	echo "
				</tr>
			</thead>
		<tbody>";
	
	$prevsortorder = -1;
	foreach( $scores as $team => $totals ) {
		if ( !empty($limitteams) && !in_array($team,$limitteams) ) continue;

		echo "
			<tr>
				<td>";
		if ( ! $displayrank ) {
			echo jurylink(null,'?');
		} elseif ( !isset($prevteam) || $scores[$prevteam]['rank']!=$totals['rank'] ) {
			echo jurylink(null,$totals['rank']);
		} else {
			echo jurylink(null,'');
		}
		$prevteam = $team;
		echo "</td>
				<td>" . htmlspecialchars($scores[$team]['teamname']) . "</td>
				<td class=\"text-center\">" . jurylink(null,$totals['num_correct']) . "/" . jurylink(null,$totals['total_time'] ) . "</td>";

		foreach( $probs as $prob => $value) {
			$bg = "";
			if( $matrix[$team][$value['probid']]['is_correct'] )
				$bg = " bg-lightGreen";
			else if ( $matrix[$team][$value['probid']]['num_submissions'] > 0 )
				$bg = " bg-red";
			echo "
				<td class=\"text-center$bg\">";
			$str = $matrix[$team][$value['probid']]['num_submissions'];
			if( $matrix[$team][$value['probid']]['num_pending'] > 0 && $SHOW_PENDING ) {
				$str .= ' + ' . $matrix[$team][$value['probid']]['num_pending'];
			}
			if( $matrix[$team][$value['probid']]['is_correct'] ) {
				$str .= ' (' . $matrix[$team][$value['probid']]['time'] . ' + ' .
				               $matrix[$team][$value['probid']]['penalty'] . ')';
			}
			echo $str . '</td>';
		}
		echo "
			</tr>";
	}

	if ( empty($limitteams) ) {
		echo "
			<tr title=\"#correct" . ( $SHOW_PENDING ? ' + #pending' : '' ) . " / #submitted / fastest time\">
				<td title=\"total teams\">" . jurylink(null,count($matrix)) . "</td>
				<td>" . jurylink(null,'Summary') . "</td>
				<td class=\"text-center\" title=\"total solved\">" . jurylink(null,$summary['num_correct'])  . "</td>";

		foreach( $probs as $prob => $value) {
			$str = $summary['problems'][$value['probid']]['num_correct'] .
			       ( $SHOW_PENDING ? ' + ' .
			         $summary['problems'][$value['probid']]['num_pending'] : '' ) . ' / ' .
			       $summary['problems'][$value['probid']]['num_submissions'] . ' / ' .
				   ( isset($summary['problems'][$value['probid']]['best_time']) ?
					 $summary['problems'][$value['probid']]['best_time'] : '-' );
			echo "
				<td class=\"text-center\">" . jurylink('problem.php?id=' . urlencode($value['probid']),$str) . "</td>";
		}
		echo "
			</tr>";
	}
	echo "
		</tbody>
	</table>";
}

function putScoreBoard()
{
	define('IS_JURY', false);
	$connect = newConnection();
	$sdata = genScoreBoard($connect);

	$categids = newQuery($connect, 'SELECT categoryid, name FROM team_category WHERE visible = 1 ');
	$affils = newQuery($connect, 'SELECT affilid AS ARRAYKEY, team_affiliation.name, country
			  FROM team_affiliation
			  JOIN team USING(affilid)
			  WHERE categoryid IN (SELECT categoryid FROM team_category WHERE visible = 1)
			  GROUP BY affilid');

	$affilids  = array();
	$countries = array();
	foreach( $affils as $id => $affil ) {
		$affilids[$id]  = $affil['name'];
		$countries[] = $affil['country'];
	}

	$countries = array_unique($countries);
	sort($countries);

	renderScoreBoardTable($sdata);
	
	/*
	$lastupdate = time();
	echo "<p id=\"lastmod\">Last Update: " . date('j M Y H:i', $lastupdate) . "<br />\n";
	//*/
	return;
}

function jurylink($target, $content) {

	$res = "";
	if ( IS_JURY ) {
		$res .= '<a' . (isset($target) ? ' href="' . $target . '"' : '' ) . '>';
	}
	$res .= $content;
	if ( IS_JURY ) $res .= '</a>';

	return $res;
}


function cmpscore($a, $b) {
	if ( $a['num_correct'] != $b['num_correct'] ) {
		return $a['num_correct'] > $b['num_correct'] ? -1 : 1;
	}
	if ( $a['total_time'] != $b['total_time'] ) {
		return $a['total_time'] < $b['total_time'] ? -1 : 1;
	}
	$atimes = $a['solve_times'];
	$btimes = $b['solve_times'];
	rsort($atimes);
	rsort($btimes);
	for($i = 0; $i < count($atimes); $i++) {
		if ( $atimes[$i] != $btimes[$i] ) return $atimes[$i] < $btimes[$i] ? -1 : 1;
	}
	return 0;
}

function cmp($a, $b) {
	if ( $a['sortorder'] != $b['sortorder'] ) {
		return $a['sortorder'] < $b['sortorder'] ? -1 : 1;
	}
	$scorecmp = cmpscore($a, $b);
	if ( $scorecmp != 0 ) return $scorecmp;
	if ( $a['teamname'] != $b['teamname'] ) {
		return strcasecmp($a['teamname'],$b['teamname']);
	}
	return 0;
}

/*
select ct.cid, a.login, a.name, b.probid, 
(case when d.minCorrect is null then (case when c.teamid is null then 0 else c.totalSubmission end) else d.minCorrect end) as submissions, 
(case when d.timeCorrect is null then 0 else (case when ct.starttime < d.timeCorrect then TIMESTAMPDIFF(MINUTE, ct.starttime, d.timeCorrect) else 0 end) end) as penaltytime, 
(case when d.timeCorrect is null then false else true end) isAccepted from team a
inner join problem b on b.cid=3
inner join contest ct on b.cid=ct.cid
left join (
	select teamid, probid, count(*) as totalSubmission from submission group by teamid, probid
) c on a.login=c.teamid and b.probid=c.probid
left join (
	select a.teamid, a.probid, MAX(submittime) timeCorrect, count(*) as minCorrect from submission a
	inner join (
		select a.submitid, a.teamid, a.probid, b.result
		from submission a
		inner join judging b on a.submitid=b.submitid
		where result='correct'
		group by teamid, probid
	) b on a.submitid <=b.submitid and a.teamid=b.teamid and a.probid=b.probid
	group by teamid, probid
) d on a.login=d.teamid and b.probid=d.probid
*/