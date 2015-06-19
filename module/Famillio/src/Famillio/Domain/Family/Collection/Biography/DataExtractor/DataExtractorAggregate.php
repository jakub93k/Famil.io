<?php
/**
 * Created by PhpStorm.
 * User: adamgrabek
 * Date: 19/06/15
 * Time: 11:14
 */

namespace Famillio\Domain\Family\Collection\Biography\DataExtractor;


use AGmakonts\STL\ValueObjectInterface;
use Famillio\Domain\Family\Biography\Fact\FactInterface;

/**
 * Class DataExtractorAggregate
 *
 * Aggregates different data extractors and allows for extraction of multiple
 * values during single run.
 *
 * All classes that implement DataExctractorInterface can be registered as
 * child extractors.
 *
 * @package Famillio\Domain\Family\Collection\Biography\DataExtractor
 */
class DataExtractorAggregate implements DataExtractorInterface
{

    private $extractors;

    /**
     * DataExtractorAggregate constructor.
     *
     * @param $extractors
     */
    public function __construct(array $extractors)
    {
        $this->extractors = new \SplObjectStorage();

        foreach ($extractors as $extractor) {
            if(FALSE === ($extractor instanceof DataExtractorInterface)) {

            }

            $this->registerExtractor($extractor);
        }
    }

    /**
     * @param \Famillio\Domain\Family\Collection\Biography\DataExtractor\DataExtractorInterface $dataExtractorInterface
     */
    public function registerExtractor(DataExtractorInterface $dataExtractorInterface)
    {
        $this->extractors()->attach($dataExtractorInterface);
    }


    /**
     * @param \Famillio\Domain\Family\Biography\Fact\FactInterface $factInterface
     *
     * @return void
     */
    public function registerFact(FactInterface $factInterface)
    {
        /** @var \Famillio\Domain\Family\Collection\Biography\DataExtractor\DataExtractorInterface $extractor */
        foreach ($this->extractors() as $extractor) {
            $extractor->registerFact($factInterface);
        }
    }

    /**
     * @return bool
     */
    public function isSatisfied() : bool
    {
        $satisfied = FALSE;

        /** @var \Famillio\Domain\Family\Collection\Biography\DataExtractor\DataExtractorInterface $extractor */
        foreach ($this->extractors() as $extractor) {
            $satisfied[] = $extractor->isSatisfied();
        }

        return (FALSE === in_array(FALSE, $satisfied));
    }

    /**
     * @return \AGmakonts\STL\ValueObjectInterface
     */
    public function data() : ValueObjectInterface
    {
        // exception
    }

    /**
     * @return \SplObjectStorage
     */
    public function extractors() : \SplObjectStorage
    {
        return $this->extractors;
    }

}