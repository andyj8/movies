<?php

namespace MovieApps\Service\Library\Response;

use MovieApps\Response\Collection;
use MovieApps\Response\KeyValuePairCollection;

class LibraryItemPayloadFactory
{
    /**
     * @param array $data
     * @param array $libraryData
     * @return mixed
     */
    public function createPayload(array $data, array $libraryData)
    {
        $response = array_merge($data, $libraryData);

        $response['isHD'] = ($libraryData['quality'] == 'HD') ? 'true' : 'false';

        if (!$response['metaValues'] instanceof KeyValuePairCollection) {
            $response['metaValues'] = new KeyValuePairCollection();
        }

        $this->addLicenseMeta($libraryData, $response['metaValues']);

        return $response;
    }

    /**
     * @param array $libraryData
     * @param KeyValuePairCollection $collection
     * @return Collection
     */
    private function addLicenseMeta(array $libraryData, KeyValuePairCollection $collection)
    {
        $collection->addRow('TotalLicenses', $libraryData['totalLicenses']);
        $collection->addRow('LicensesUsed', $libraryData['licensesUsed']);
        $collection->addRow('LicensesRemaining', $libraryData['licensesRemaining']);
        $collection->addRow('LicensesDelivered', $libraryData['licensesDelivered']);
    }
}

//<TitleClassification>Movie</TitleClassification>
//<HasHD>true</HasHD>
//<HasUV>false</HasUV>
//<ReleaseYear>1985</ReleaseYear>
//<IsTentpoleTitle>false</IsTentpoleTitle>
