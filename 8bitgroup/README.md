Need to implement Symfony2 bundle for getting JSON-encoded locations data stored in predefined format.
Acceptance criteria
Client should be defined as a service class in a bundle config;
Client should utilize CURL as a transport layer (can rely upon any third-party bundle however should be implemented as a separate class/package);
Properly defined exceptions should be thrown on CURL errors, malformed JSON response and error JSON response;
Resulting data should be fetched as an array (or other collection) of properly defined PHP objects.
JSON response format
```
{
   “data”: {
       “locations”: [
           {
               “name”: “Eiffel Tower”,
               “coordinates”: {
                   “lat”: 21.12,
                   “long”: 19.56
               }
           },
           ...
       ]
   },
   “success”: true
}
```

JSON error response format
```
 {
   “data”: {
       “message”: <string error message>,
       “code”: <string error code>
   },
   “success”: false
 }
```

## Usage ##

Write config:

```
yaroslav_json_mapper:
    mappers:
        test1:
            url: http://127.0.0.1:8001/
            mapClass: "AppBundle/Entity/LocationCollection"
            mapping: 
                locations:
                    type: "AppBundle/Entity/Location[]"
                   mapping: 
                        name:

                            type: string
                        coordinates:
                            type: "AppBundle/Entity/Coordinates"
                            mapping:
                                long:
                                   type: float
                                lat:
                                   type: float

```

## Invoke ##

```
   /**
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {
        $mapper = $this->container->get("yaroslav_json_mapper_test1");
        return new \Symfony\Component\HttpFoundation\Response(print_r($mapper->findAll(), 
                true));
    }
```

## Ouput ##
```
AppBundle\Entity\LocationCollection Object
(
    [locations:AppBundle\Entity\LocationCollection:private] => Array
        (
            [0] => AppBundle\Entity\Location Object
                (
                    [id:AppBundle\Entity\Location:private] => 
                    [name:AppBundle\Entity\Location:private] => Eiffel Tower
                    [coordinates:AppBundle\Entity\Location:private] => AppBundle\Entity\Coordinates Object
                        (
                            [id:AppBundle\Entity\Coordinates:private] => 
                            [long:AppBundle\Entity\Coordinates:private] => 19.56
                            [lat:AppBundle\Entity\Coordinates:private] => 21.12
                        )

                )

            [1] => AppBundle\Entity\Location Object
                (
                    [id:AppBundle\Entity\Location:private] => 
                    [name:AppBundle\Entity\Location:private] => House
                    [coordinates:AppBundle\Entity\Location:private] => AppBundle\Entity\Coordinates Object
                        (
                            [id:AppBundle\Entity\Coordinates:private] =>                             [long:AppBundle\Entity\Coordinates:private] => 0.56
                            [lat:AppBundle\Entity\Coordinates:private] => 0.12
                        )
                )
        )
)
```


