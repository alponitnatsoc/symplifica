<?php

namespace RocketSeller\TwoPickBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RocketSeller\TwoPickBundle\Entity\NoveltyType;

class LoadNoveltyTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

      /* All the entries with type absenteeism */
        $NoveltyMaternity = new NoveltyType();
        $NoveltyMaternity->setName('Licencia de maternidad');
        $NoveltyMaternity->setPayrollCode('1');
        $NoveltyMaternity->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyMaternity);

        $NoveltyUnpaid = new NoveltyType();
        $NoveltyUnpaid->setName('Licencia no remunerada');
        $NoveltyUnpaid->setPayrollCode('3');
        $NoveltyUnpaid->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyUnpaid);

        $NoveltyPaid = new NoveltyType();
        $NoveltyPaid->setName('Licencia remunerada');
        $NoveltyPaid->setPayrollCode('5');
        $NoveltyPaid->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyPaid);

        $NoveltySuspension = new NoveltyType();
        $NoveltySuspension->setName('Suspension');
        $NoveltySuspension->setPayrollCode('6');
        $NoveltySuspension->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltySuspension);

        $NoveltyGeneralIllness = new NoveltyType();
        $NoveltyGeneralIllness->setName('Enfermedad general');
        $NoveltyGeneralIllness->setPayrollCode('7');
        $NoveltyGeneralIllness->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyGeneralIllness);

        $NoveltyWorkAccident = new NoveltyType();
        $NoveltyWorkAccident->setName('Accidente de trabajo');
        $NoveltyWorkAccident->setPayrollCode('8');
        $NoveltyWorkAccident->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyWorkAccident);

        $NoveltyProfessionalIllness = new NoveltyType();
        $NoveltyProfessionalIllness->setName('Enfermedad profesional');
        $NoveltyProfessionalIllness->setPayrollCode('9');
        $NoveltyProfessionalIllness->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyProfessionalIllness);

        $NoveltyPaternityLeave = new NoveltyType();
        $NoveltyPaternityLeave->setName('Licencia de paternidad');
        $NoveltyPaternityLeave->setPayrollCode('10');
        $NoveltyPaternityLeave->setAbsenteeismOrNovelty('absenteeism');
        $manager->persist($NoveltyPaternityLeave);

       /* All the entries with type Novelty */
        $NoveltySalaryAdjust = new NoveltyType();
        $NoveltySalaryAdjust->setName('Ajuste sueldo');
        $NoveltySalaryAdjust->setPayrollCode('2');
        $NoveltySalaryAdjust->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltySalaryAdjust);

        $NoveltyBonus = new NoveltyType();
        $NoveltyBonus->setName('Bonificación');
        $NoveltyBonus->setPayrollCode('30');
        $NoveltyBonus->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyBonus);

        $NoveltyNightCharge = new NoveltyType();
        $NoveltyNightCharge->setName('Recargo nocturno');
        $NoveltyNightCharge->setPayrollCode('45');
        $NoveltyNightCharge->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyNightCharge);

        $NoveltyNightHolidayCharge = new NoveltyType();
        $NoveltyNightHolidayCharge->setName('Recargo nocturno festivo');
        $NoveltyNightHolidayCharge->setPayrollCode('52');
        $NoveltyNightHolidayCharge->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyNightHolidayCharge);

        $NoveltyExtraHour = new NoveltyType();
        $NoveltyExtraHour->setName('Hora extra diurna');
        $NoveltyExtraHour->setPayrollCode('55');
        $NoveltyExtraHour->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyExtraHour);

        $NoveltyExtraHourNight = new NoveltyType();
        $NoveltyExtraHourNight->setName('Hora extra nocturna');
        $NoveltyExtraHourNight->setPayrollCode('60');
        $NoveltyExtraHourNight->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyExtraHourNight);

        $NoveltyExtraHourHoliday = new NoveltyType();
        $NoveltyExtraHourHoliday->setName('Hora extra festiva diurna');
        $NoveltyExtraHourHoliday->setPayrollCode('65');
        $NoveltyExtraHourHoliday->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyExtraHourHoliday);

        $NoveltyHoliday = new NoveltyType();
        $NoveltyHoliday->setName('Festivo diurno');
        $NoveltyHoliday->setPayrollCode('66');
        $NoveltyHoliday->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyHoliday);

        $NoveltyHolidayNightExtraHour = new NoveltyType();
        $NoveltyHolidayNightExtraHour->setName('Hora extra festiva nocturna');
        $NoveltyHolidayNightExtraHour->setPayrollCode('70');
        $NoveltyHolidayNightExtraHour->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyHolidayNightExtraHour);

        $NoveltyCompensatoryRest = new NoveltyType();
        $NoveltyCompensatoryRest->setName('Descanso compensatorio');
        $NoveltyCompensatoryRest->setPayrollCode('85');
        $NoveltyCompensatoryRest->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyCompensatoryRest);

        $NoveltyTransport = new NoveltyType();
        $NoveltyTransport->setName('Subsidio de transporte');
        $NoveltyTransport->setPayrollCode('120');
        $NoveltyTransport->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyTransport);

        $NoveltyVacation = new NoveltyType();
        $NoveltyVacation->setName('Vacaciones');
        $NoveltyVacation->setPayrollCode('145');
        $NoveltyVacation->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyVacation);

        $NoveltyVacationMoney = new NoveltyType();
        $NoveltyVacationMoney->setName('Vacaciones en dinero');
        $NoveltyVacationMoney->setPayrollCode('150');
        $NoveltyVacationMoney->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyVacationMoney);

        $NoveltyLibertyBonus = new NoveltyType();
        $NoveltyLibertyBonus->setName('Bonificacion mera liberalidad');
        $NoveltyLibertyBonus->setPayrollCode('285');
        $NoveltyLibertyBonus->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyLibertyBonus);

        $NoveltyDiscountLoan = new NoveltyType();
        $NoveltyDiscountLoan->setName('Descuento prestamos');
        $NoveltyDiscountLoan->setPayrollCode('4810');
        $NoveltyDiscountLoan->setAbsenteeismOrNovelty('novelty');
        $manager->persist($NoveltyDiscountLoan);

        $manager->flush();

        // Abstenteeisms.
        $this->addReference('novlety-maternity', $NoveltyMaternity);
        $this->addReference('novlety-unpaid', $NoveltyUnpaid);
        $this->addReference('novlety-paid', $NoveltyPaid);
        $this->addReference('novlety-suspension', $NoveltySuspension);
        $this->addReference('novlety-general-illness', $NoveltyGeneralIllness);
        $this->addReference('novlety-work-accident', $NoveltyWorkAccident);
        $this->addReference('novlety-professional-illness', $NoveltyProfessionalIllness);
        $this->addReference('novlety-paternity-leave', $NoveltyPaternityLeave);

        // Nolvelty.
        $this->addReference('novlety-salary-adjust', $NoveltySalaryAdjust);
        $this->addReference('novlety-bonus', $NoveltyBonus);
        $this->addReference('novlety-night-charge', $NoveltyNightCharge);
        $this->addReference('novlety-night-holiday-charge', $NoveltyNightHolidayCharge);
        $this->addReference('novlety-extra-hour', $NoveltyExtraHour);
        $this->addReference('novlety-extra-hour-night', $NoveltyExtraHourNight);
        $this->addReference('novlety-extra-hour-holiday', $NoveltyExtraHourHoliday);
        $this->addReference('novlety-holiday', $NoveltyHoliday);
        $this->addReference('novlety-holiday-night-extra-hour', $NoveltyHolidayNightExtraHour);
        $this->addReference('novlety-compensatory-rest', $NoveltyCompensatoryRest);
        $this->addReference('novlety-transport', $NoveltyTransport);
        $this->addReference('novlety-vacation', $NoveltyVacation);
        $this->addReference('novlety-vacation-money', $NoveltyVacationMoney);
        $this->addReference('novlety-liberty-bonus', $NoveltyLibertyBonus);
        $this->addReference('novlety-discount-loan', $NoveltyDiscountLoan);
    }
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 9;
    }
}
