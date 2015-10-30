<?php
/**
 * @author Martin Kovařčík.
 */

namespace Admin;

use App\Provider\IArrayProvider;
use Model\CompetitionsFacade;
use Model\SectionFilter;
use Nette\Object;

class CompetitionsProvider extends Object implements IArrayProvider
{
	/** @var */
	private $sectionId;

	/** @var \Model\CompetitionsFacade */
	private $competitionsFacade;

	/** @var \Model\SectionFilter */
	private $sectionFilter;

	/** @var \Admin\CompetitionsOrderer */
	private $competitionsOrderer;

	/**
	 * @param $sectionId
	 * @param \Model\CompetitionsFacade $competitionsFacade
	 * @param \Model\SectionFilter $sectionFilter
	 * @param \Admin\CompetitionsOrderer $competitionsOrderer
	 */
	public function __construct($sectionId, CompetitionsFacade $competitionsFacade, SectionFilter $sectionFilter, CompetitionsOrderer $competitionsOrderer)
	{
		$this->sectionId = $sectionId;
		$this->competitionsFacade = $competitionsFacade;
		$this->sectionFilter = $sectionFilter;
		$this->competitionsOrderer = $competitionsOrderer;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		$selection = $this->sectionFilter->filterId($this->competitionsFacade->all(), $this->sectionId, 'hosys_regions');
		$this->competitionsOrderer->order($selection, 'competitions');

		return $selection->fetchPairs('id', 'jmeno');
	}

}

interface ICompetitionsProviderFactory
{
	/**
	 * @param $sectionId
	 * @return \Admin\CompetitionsProvider
	 */
	public function create($sectionId);
}
