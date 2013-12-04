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
	
	$cachetable = 'scoreboard_jury';
	
	$scoredata = newQuery($connect, "SELECT * FROM $cachetable WHERE cid = :cid", array('cid' => $cid));
	
	foreach ($teams as $login => $team ) {
		$SCORES[$login]['num_correct'] = 0;
		$SCORES[$login]['total_time']  = 0;
		$SCORES[$login]['solve_times'] = array();
		$SCORES[$login]['rank']        = 0;
		$SCORES[$login]['teamname']    = $team['name'];
		$SCORES[$login]['categoryid']  = $team['categoryid'];
		$SCORES[$login]['sortorder']   = $team['sortorder'];
		$SCORES[$login]['affilid']     = $team['affilid'];
		$SCORES[$login]['country']     = $team['country'];
	}

	// initialize all problems with data
	foreach( $probs as $prob => $value) {
		if ( !isset($SUMMARY['problems'][$prob]) ) {
			$SUMMARY['problems'][$prob]['num_submissions'] = 0;
			$SUMMARY['problems'][$prob]['num_pending'] = 0;
			$SUMMARY['problems'][$prob]['num_correct'] = 0;
			$SUMMARY['problems'][$prob]['best_time'] = NULL;
			$SUMMARY['problems'][$prob]['best_time_sort'] = array();
		}
	}

	// loop all info the scoreboard cache and put it in our own datastructure
	while ( $srow = $scoredata->fetch() ) {

		// skip this row if the team or problem is not known by us
		if ( ! array_key_exists ( $srow['teamid'], $teams ) ||
		     ! array_key_exists ( $srow['probid'], $probs ) ) continue;

		$penalty = getPenaltyTime( $srow['is_correct'], $srow['submissions'] );

		// fill our matrix with the scores from the database
		$MATRIX[$srow['teamid']][$srow['probid']] = array (
			'is_correct'      => (bool) $srow['is_correct'],
			'num_submissions' => $srow['submissions'],
			'num_pending'     => $srow['pending'],
			'time'            => $srow['totaltime'],
			'penalty'         => $penalty );

		// calculate totals for this team
		if ( $srow['is_correct'] ) {
			$SCORES[$srow['teamid']]['num_correct']++;
			$SCORES[$srow['teamid']]['solve_times'][] = $srow['totaltime'];
			$SCORES[$srow['teamid']]['total_time'] += $srow['totaltime'] + $penalty;
		}
	}

	// sort the array using our custom comparison function
	uasort($SCORES, 'cmp');

	// loop over all teams to calculate ranks and totals
	$prevsortorder = -1;
	foreach( $SCORES as $team => $totals ) {

		// rank, team name, total correct, total time
		if ( $totals['sortorder'] != $prevsortorder ) {
			$prevsortorder = $totals['sortorder'];
			$rank = 0; // reset team position on switch to different category
			$prevteam = null;
		}
		$rank++;
		// Use previous' team rank when scores are equal
		if ( isset($prevteam) && cmpscore($SCORES[$prevteam], $totals)==0 ) {
			$SCORES[$team]['rank'] = $SCORES[$prevteam]['rank'];
		} else {
			$SCORES[$team]['rank'] = $rank;
		}
		$prevteam = $team;

		// keep summary statistics for the bottom row of our table
		$SUMMARY['num_correct'] += $totals['num_correct'];
		//if ( ! empty($teams[$team]['affilid']) ) @$SUMMARY['affils'][$totals['affilid']]++;
		//if ( ! empty($teams[$team]['country']) ) @$SUMMARY['countries'][$totals['country']]++;

		// for each problem
		foreach( $probs as $prob => $value) {
		//foreach ( array_keys($probs) as $prob ) {

			// provide default scores when nothing submitted for this team,problem yet
			if ( ! isset ( $MATRIX[$team][$prob] ) ) {
				$MATRIX[$team][$prob] = array('num_submissions' => 0, 'num_pending' => 0,
				                              'is_correct' => 0, 'time' => 0, 'penalty' => 0);
			}
			$pdata = $MATRIX[$team][$prob];
			$psum = &$SUMMARY['problems'][$prob];

			// update summary data for the bottom row
			@$psum['num_submissions'] += $pdata['num_submissions'];
			@$psum['num_pending'] += $pdata['num_pending'];
			@$psum['num_correct'] += ($pdata['is_correct'] ? 1 : 0);

			if ( $pdata['is_correct'] ) {
				// store per sortorder the first solve time
				if ( !isset($psum['best_time_sort'][$totals['sortorder']]) ||
				     $pdata['time']<$psum['best_time_sort'][$totals['sortorder']] ) {
					@$psum['best_time_sort'][$totals['sortorder']] = $pdata['time'];
				}

				// also keep overall best time per problem for in bottom summary row
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

/**
 * Output the general scoreboard based on the cached data in table
 * 'scoreboard_{team,jury}'. $myteamid can be passed to highlight a
 * specific row.
 * If this function is called while IS_JURY is defined, the scoreboard
 * will always be current, regardless of the freezetime setting in the
 * contesttable.
 * $static generates output suitable for standalone static html pages,
 * that is without references/links to other parts of the DOMjudge
 * interface.
 * $limitteams is an array of teamid's whose rows will be the only
 * ones displayed. The function still needs the complete scoreboard
 * data or it will not know the rank.
 * if $displayrank is false the first column will not display the
 * team's current rank but a question mark.
 */
function renderScoreBoardTable($sdata)
{
	$cid = $_GET['contest'];
	$static = true;
	$showlegends = false;
	$displayrank = true;

	// 'unpack' the scoreboard data:
	$scores  = $sdata['scores'];
	$matrix  = $sdata['matrix'];
	$summary = $sdata['summary'];
	$teams   = $sdata['teams'];
	$probs   = $sdata['problems'];
	$categs  = $sdata['categories'];
	unset($sdata);

	// configuration
	$SHOW_AFFILIATIONS = 0;
	$SHOW_PENDING      = 0;

	echo "
		<table class=\"table hovered\">
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
	
	// print the main scoreboard rows
	$prevsortorder = -1;
	foreach( $scores as $team => $totals ) {
		// skip if we have limitteams and the team is not listed
		if ( !empty($limitteams) && !in_array($team,$limitteams) ) continue;

		// rank, team name, total correct, total time
		echo "
			<tr>
				<td>";
		// Only print rank when score is different from the previous team
		if ( ! $displayrank ) {
			echo jurylink(null,'?');
		} elseif ( !isset($prevteam) || $scores[$prevteam]['rank']!=$totals['rank'] ) {
			echo jurylink(null,$totals['rank']);
		} else {
			echo jurylink(null,'');
		}
		$prevteam = $team;
		echo "</td>
				<td>" . htmlspecialchars($teams[$team]['name']) . "</td>
				<td class=\"text-center\">" . jurylink(null,$totals['num_correct']) . "/" . jurylink(null,$totals['total_time'] ) . "</td>";

		// for each problem
		foreach( $probs as $prob => $value) {
			echo "
				<td class=\"text-center\">";
			// number of submissions for this problem
			$str = $matrix[$team][$prob]['num_submissions'];
			// add pending submissions
			if( $matrix[$team][$prob]['num_pending'] > 0 && $SHOW_PENDING ) {
				$str .= ' + ' . $matrix[$team][$prob]['num_pending'];
			}
			// if correct, print time scored
			if( $matrix[$team][$prob]['is_correct'] ) {
				$str .= ' (' . $matrix[$team][$prob]['time'] . ' + ' .
				               $matrix[$team][$prob]['penalty'] . ')';
			}
			echo $str . '</td>';
		}
		echo "
			</tr>";
	}

	if ( empty($limitteams) ) {
		// print a summaryline
		echo "
			<tr title=\"#submitted" . ( $SHOW_PENDING ? ' + #pending' : '' ) . " / #correct / fastest time\">
				<td title=\"total teams\">" . jurylink(null,count($matrix)) . "</td>
				<td>" . jurylink(null,'Summary') . "</td>
				<td class=\"text-center\" title=\"total solved\">" . jurylink(null,$summary['num_correct'])  . "</td>";

		//foreach( array_keys($probs) as $prob ) {
		foreach( $probs as $prob => $value) {
			$str = $summary['problems'][$prob]['num_submissions'] .
			       ( $SHOW_PENDING ? ' + ' .
			         $summary['problems'][$prob]['num_pending'] : '' ) . ' / ' .
			       $summary['problems'][$prob]['num_correct'] . ' / ' .
				   ( isset($summary['problems'][$prob]['best_time']) ?
					 $summary['problems'][$prob]['best_time'] : '-' );
			echo "
				<td class=\"text-center\">" . jurylink('problem.php?id=' . urlencode($prob),$str) . "</td>";
		}
		echo "
			</tr>";
	}
	echo "
		</tbody>
	</table>";
}

/**
 * Function to output a complete scoreboard.
 * This takes care of outputting the headings, start/endtimes and footer
 * of the scoreboard. It calls genScoreBoard to generate the data and
 * renderScoreBoardTable for displaying the actual table.
 *
 * Arguments:
 * $cdata       current contest data, as from 'getCurContest(TRUE)'
 * $myteamid    set to highlight that teamid in the scoreboard
 * $static      generate a static scoreboard, e.g. for external use
 * $filter      set to TRUE to generate filter options, or pass array
 *              with keys 'affilid', 'country', 'categoryid' pointing
 *              to array of values to filter on these.
 */
function putScoreBoard()
{
	define('IS_JURY', false);
	$connect = newConnection();
	$sdata = genScoreBoard($connect);
	//$res = newQuery($connect, "select * from contest where cid=:cid", array('cid' => $_GET['contest']));
	//$row = $res->fetch();

	// page heading with contestname and start/endtimes
		//echo "<h4>starts: $row[starttime] - ends: $row[endtime]</h4>";

		$categids = newQuery($connect, 'SELECT categoryid, name FROM team_category WHERE visible = 1 ');
		// show only affilids/countries with visible teams
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

	// last modified date, now if we are the jury, else include the
	// freeze time
	$lastupdate = time();
	echo "<p id=\"lastmod\">Last Update: " .
	     date('j M Y H:i', $lastupdate) . "<br />\n";
	return;
}

/**
 * Given an array of contest data, calculates whether the contest
 * has already started ('cstarted'), and if scoreboard is currently
 * frozen ('showfrozen') or final ('showfinal').
 */
function calcFreezeData($cdata)
{
	$fdata = array();

	// Show final scores if contest is over and unfreezetime has been
	// reached, or if contest is over and no freezetime had been set.
	// We can compare $now and the dbfields stringwise.
	$now = now();
	$fdata['showfinal']  = ( !isset($cdata['freezetime']) &&
	                difftime($cdata['endtime'],$now) <= 0 ) ||
	              ( isset($cdata['unfreezetime']) &&
	                difftime($cdata['unfreezetime'], $now) <= 0 );
	// freeze scoreboard if freeze time has been reached and
	// we're not showing the final score yet
	$fdata['showfrozen'] = !$fdata['showfinal'] && isset($cdata['freezetime']) &&
	              difftime($cdata['freezetime'],$now) <= 0;
	// contest is active but has not yet started
	$fdata['cstarted'] = difftime($cdata['starttime'],$now) <= 0;

	return $fdata;
}

/**
 * Output a team row from the scoreboard based on the cached data in
 * table 'scoreboard'.
 */
function putTeamRow($cdata, $teamids) {

	if ( empty($cdata) ) return;

	$fdata = calcFreezeData($cdata);

	if ( ! $fdata['cstarted'] ) {
		if ( ! IS_JURY ) {

			global $teamdata;
			echo "<h2 id=\"teamwelcome\">welcome team <span id=\"teamwelcometeam\">" .
				htmlspecialchars($teamdata['name']) . "</span>!</h2>\n\n";
			echo "<h3 id=\"contestnotstarted\">contest is scheduled to start at " .
				printtime($cdata['starttime']) . "</h3>\n\n";
		}

		return;
	}

	// Calculate scoreboard as jury to display non-visible teams:
	$sdata = genScoreBoard($cdata, TRUE);

	$myteamid = null;
	$static = FALSE;
	$displayrank = !$fdata['showfrozen'];

	if ( ! IS_JURY ) echo "<div id=\"teamscoresummary\">\n";
	renderScoreBoardTable($cdata,$sdata,$myteamid,$static,
	                      $teamids,$displayrank,TRUE,FALSE);
	if ( ! IS_JURY ) echo "</div>\n\n";

	return;
}

/**
 * Generate scoreboard links for jury only.
 */
function jurylink($target, $content) {

	$res = "";
	if ( IS_JURY ) {
		$res .= '<a' . (isset($target) ? ' href="' . $target . '"' : '' ) . '>';
	}
	$res .= $content;
	if ( IS_JURY ) $res .= '</a>';

	return $res;
}

/**
 * Main score comparison function, called from the 'cmp' wrapper
 * below. Scores two arrays, $a and $b, based on the following
 * criteria:
 * - highest number of correct solutions;
 * - least amount of total time spent on these solutions;
 * - fastest submission time for their most recent correct solution.
 */
function cmpscore($a, $b) {
	// more correct than someone else means higher rank
	if ( $a['num_correct'] != $b['num_correct'] ) {
		return $a['num_correct'] > $b['num_correct'] ? -1 : 1;
	}
	// else, less time spent means higher rank
	if ( $a['total_time'] != $b['total_time'] ) {
		return $a['total_time'] < $b['total_time'] ? -1 : 1;
	}
	// else tie-breaker rule: fastest submission time for latest
	// correct problem, when times are equal, compare one-to-latest,
	// etc...
	$atimes = $a['solve_times'];
	$btimes = $b['solve_times'];
	rsort($atimes);
	rsort($btimes);
	for($i = 0; $i < count($atimes); $i++) {
		if ( $atimes[$i] != $btimes[$i] ) return $atimes[$i] < $btimes[$i] ? -1 : 1;
	}
	return 0;
}

/**
 * Scoreboard sorting function. Given two arrays with team information
 * $a and $b, decides on how to order these. It uses the following
 * criteria:
 * - First, use the sortorder override from the team_category table
 *   (e.g. score regular contestants always over spectators);
 * - Then, use the cmpscore function to determine the actual ordering
 *   based on number of problems solved and the time it took;
 * - If still equal, order on team name alphabetically.
 */
function cmp($a, $b) {
	// first order by our predefined sortorder based on category
	if ( $a['sortorder'] != $b['sortorder'] ) {
		return $a['sortorder'] < $b['sortorder'] ? -1 : 1;
	}
	// then compare scores
	$scorecmp = cmpscore($a, $b);
	if ( $scorecmp != 0 ) return $scorecmp;
	// else, order by teamname alphabetically
	if ( $a['teamname'] != $b['teamname'] ) {
		return strcasecmp($a['teamname'],$b['teamname']);
	}
	// undecided, should never happen in practice
	return 0;
}