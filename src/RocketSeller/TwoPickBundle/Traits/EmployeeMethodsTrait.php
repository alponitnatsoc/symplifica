<?php
namespace RocketSeller\TwoPickBundle\Traits;

trait EmployeeMethodsTrait
{
    protected function getEmployeeDetails($id)
    {
        $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee');
        $employee = $repository->find($id);

        return $employee;
    }

    protected function getEmployeeEps($id)
    {
        $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee');
        /** @var \RocketSeller\TwoPickBundle\Entity\Employee $employee */
        $employee = $repository->find($id);
        $entities = $employee->getEntities();
        /** @var \RocketSeller\TwoPickBundle\Entity\EmployeeHasEntity $entity */
        foreach ($entities as $entity) {
            if ($entity->getEntityEntity()->getEntityTypeEntityType()->getPayrollCode() == "EPS") {
                return $entity->getEntityEntity();
            }
        }

        return null;
    }

    protected function getEmployeeAfp($id)
    {
        $repository = $this->getDoctrine()->getRepository('RocketSellerTwoPickBundle:Employee');
        /** @var \RocketSeller\TwoPickBundle\Entity\Employee $employee */
        $employee = $repository->find($id);
        $entities = $employee->getEntities();
        /** @var \RocketSeller\TwoPickBundle\Entity\EmployeeHasEntity $entity */
        foreach ($entities as $entity) {
            if ($entity->getEntityEntity()->getEntityTypeEntityType()->getPayrollCode() == "AFP") {
                return $entity->getEntityEntity();
            }
        }

        return null;
    }
}