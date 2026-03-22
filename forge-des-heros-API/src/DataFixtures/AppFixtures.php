<?php

namespace App\DataFixtures;

use App\Entity\CharacterClass;
use App\Entity\Race;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadRaces($manager);
        $this->loadCharacterClasses($manager);
        $this->loadSkills($manager);
        $this->assignSkillsToClasses($manager);

        $manager->flush();
    }

    private function loadRaces(ObjectManager $manager): void
    {
        $races = [
            ['name' => 'Humain', 'description' => 'Polyvalents et ambitieux, les humains sont la race la plus répandue.'],
            ['name' => 'Elfe', 'description' => 'Gracieux et longévifs, les elfes possèdent une affinité naturelle avec la magie.'],
            ['name' => 'Nain', 'description' => 'Robustes et tenaces, les nains sont des artisans et guerriers réputés.'],
            ['name' => 'Halfelin', 'description' => 'Petits et agiles, les halfelins sont connus pour leur chance et leur discrétion.'],
            ['name' => 'Demi-Orc', 'description' => 'Forts et endurants, les demi-orcs allient la puissance des orcs à l\'adaptabilité humaine.'],
            ['name' => 'Gnome', 'description' => 'Curieux et inventifs, les gnomes excellent dans les domaines de la magie et de la technologie.'],
            ['name' => 'Tieffelin', 'description' => 'Descendants d\'une lignée infernale, les tieffelins portent la marque de leur héritage.'],
            ['name' => 'Demi-Elfe', 'description' => 'Héritant du meilleur des deux mondes, les demi-elfes sont diplomates et polyvalents.'],
        ];

        foreach ($races as $raceData) {
            $race = $manager->getRepository(Race::class)->findOneBy(['name' => $raceData['name']]) ?? new Race();

            $race
                ->setName($raceData['name'])
                ->setDescription($raceData['description']);

            $manager->persist($race);
        }
    }

    private function loadCharacterClasses(ObjectManager $manager): void
    {
        $classes = [
            ['name' => 'Barbare', 'healthDice' => 12, 'description' => 'Guerrier sauvage animé par une rage dévastatrice.'],
            ['name' => 'Barde', 'healthDice' => 8, 'description' => 'Artiste et conteur dont la musique possède un pouvoir magique.'],
            ['name' => 'Clerc', 'healthDice' => 8, 'description' => 'Serviteur divin canalisant la puissance de sa divinité.'],
            ['name' => 'Druide', 'healthDice' => 8, 'description' => 'Gardien de la nature capable de se métamorphoser.'],
            ['name' => 'Guerrier', 'healthDice' => 10, 'description' => 'Maître des armes et des tactiques de combat.'],
            ['name' => 'Mage', 'healthDice' => 6, 'description' => 'Érudit de l\'arcane maîtrisant de puissants sortilèges.'],
            ['name' => 'Paladin', 'healthDice' => 10, 'description' => 'Chevalier sacré combinant prouesse martiale et magie divine.'],
            ['name' => 'Ranger', 'healthDice' => 10, 'description' => 'Chasseur et pisteur expert des terres sauvages.'],
            ['name' => 'Sorcier', 'healthDice' => 6, 'description' => 'Lanceur de sorts dont le pouvoir est inné et instinctif.'],
            ['name' => 'Voleur', 'healthDice' => 8, 'description' => 'Spécialiste de la discrétion, du crochetage et des attaques sournoises.'],
        ];

        foreach ($classes as $classData) {
            $characterClass = $manager->getRepository(CharacterClass::class)->findOneBy(['name' => $classData['name']]) ?? new CharacterClass();

            $characterClass
                ->setName($classData['name'])
                ->setHealthDice($classData['healthDice'])
                ->setDescription($classData['description']);

            $manager->persist($characterClass);
        }
    }

    private function loadSkills(ObjectManager $manager): void
    {
        $skills = [
            ['name' => 'Acrobaties', 'ability' => 'DEX'],
            ['name' => 'Arcanes', 'ability' => 'INT'],
            ['name' => 'Athlétisme', 'ability' => 'STR'],
            ['name' => 'Discrétion', 'ability' => 'DEX'],
            ['name' => 'Dressage', 'ability' => 'WIS'],
            ['name' => 'Escamotage', 'ability' => 'DEX'],
            ['name' => 'Histoire', 'ability' => 'INT'],
            ['name' => 'Intimidation', 'ability' => 'CHA'],
            ['name' => 'Investigation', 'ability' => 'INT'],
            ['name' => 'Médecine', 'ability' => 'WIS'],
            ['name' => 'Nature', 'ability' => 'INT'],
            ['name' => 'Perception', 'ability' => 'WIS'],
            ['name' => 'Perspicacité', 'ability' => 'WIS'],
            ['name' => 'Persuasion', 'ability' => 'CHA'],
            ['name' => 'Religion', 'ability' => 'INT'],
            ['name' => 'Représentation', 'ability' => 'CHA'],
            ['name' => 'Survie', 'ability' => 'WIS'],
            ['name' => 'Tromperie', 'ability' => 'CHA'],
        ];

        foreach ($skills as $skillData) {
            $skill = $manager->getRepository(Skill::class)->findOneBy(['name' => $skillData['name']]) ?? new Skill();

            $skill
                ->setName($skillData['name'])
                ->setAbility($skillData['ability']);

            $manager->persist($skill);
        }
    }

    private function assignSkillsToClasses(ObjectManager $manager): void
    {
        $map = [
            'Guerrier' => ['Athlétisme', 'Intimidation', 'Survie'],
            'Mage'     => ['Arcanes', 'Histoire', 'Investigation'],
            'Voleur'   => ['Acrobaties', 'Discrétion', 'Escamotage'],
            'Barbare'  => ['Athlétisme', 'Intimidation', 'Survie'],
            'Barde'    => ['Représentation', 'Persuasion', 'Tromperie'],
            'Clerc'    => ['Religion', 'Médecine', 'Perspicacité'],
            'Druide'   => ['Nature', 'Dressage', 'Survie'],
            'Paladin'  => ['Religion', 'Intimidation', 'Persuasion'],
            'Ranger'   => ['Survie', 'Perception', 'Nature'],
            'Sorcier'  => ['Arcanes', 'Tromperie', 'Persuasion'],
        ];

        foreach ($map as $className => $skillNames) {
            $characterClass = $manager->getRepository(CharacterClass::class)->findOneBy(['name' => $className]);
            if (!$characterClass) {
                continue;
            }

            foreach ($skillNames as $skillName) {
                $skill = $manager->getRepository(Skill::class)->findOneBy(['name' => $skillName]);
                if ($skill) {
                    $characterClass->addSkill($skill);
                }
            }

            $manager->persist($characterClass);
        }
    }
}
