# Work in Progress. Not ready for production.

## Documentation in progress.

Sometimes, it could be useful to get some king of "static" entities (i.e. object that we don't want to store in database)

Imagine this kind of model:

```php
class User
{
    private $firstName;
    private $lastName;
    private $gender;

    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    // ... (Getters and setters for firstName and lastName)

    static public function getGenderList()
    {
        return [
            self::GENDER_MALE => ['name' => 'Male', 'shortName' => 'M'],
            self::GENDER_FEMALE => ['name' => 'Female', 'shortName' => 'F']
        ];
    }

    static public function getGenderKeys()
    {
        return array_keys(self::getGenderList());
    }

    public function setGender($gender)
    {
        $gender = (int) $gender;

        if(!array_key_exists($gender, self::getGenderList()) {
            throw new \Exception('Bad value...');
        }

        $this->gender = $gender;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getGenderName()
    {
        $genders = $this->getGenderList();
        return $genders[$this->gender]['name'];
    }

    public function getGenderShortName()
    {
        $genders = $this->getGenderList();
        return $genders[$this->gender]['shortName'];
    }
}
```

It could be used like this:

```php
$user = new User();
$user->setFirstName('John');
$user->setLastName('Doe');
$user->setGender(User::GENDER_MALE);

printf('User: %s %s (%s)', $user->getFirstName(), $user->getLastName(), $user->getGenderShortName());
printf('Hello, it seems you are a %s', $user->getGenderName());
```

As you can see, it generally quickly becomes a nightmare to manage all possibles values, checking, getters etc.

### Here come the Static Entities

#### First step, create your static entity

```php
class Gender extends StaticEntity
{
    const MALE = 1;
    const FEMALE = 2;

    private $name;
    private $shortName;

    static public function getDataSet()
    {
        return [
            self::MALE => ['name' => 'Male', 'shortName' => 'M'],
            self::FEMALE => ['name' => 'Female', 'shortName' => 'F']
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getShortName()
    {
        return $this->shortName;
    }
}
```

#### Then update your model

```php
class User
{
    private $firstName;
    private $lastName;
    private $gender;

    // ... (Getters and setters for firstName and lastName)

    public function setGender($gender)
    {
        $this->gender = Gender::toId($gender);
    }

    public function getGender()
    {
        return Gender::get($this->gender);
    }
}
```

Much better, your Model is lightweight again :)

You can now use it like this:

```php
$user = new User();
$user->setFirstName('John');
$user->setLastName('Doe');
$user->setGender(Gender::MALE);

printf('User: %s %s (%s)', $user->getFirstName(), $user->getLastName(), $user->getGender->getShortName());
printf('Hello, it seems you are a %s', $user->getGender->getName());
```

Thanks to the `toId()` method, you can also pass a static entity instance to the setter:

```
$male = Gender::get(Gender::MALE);
$user->setGender($male);
```
