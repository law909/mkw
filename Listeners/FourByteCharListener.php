<?php

namespace Listeners;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use mkwhelpers\ParameterHandler;

// az importok és az API nem a ParameterHandleren át írnak, ezért a szűrés mentéskor is kell
class FourByteCharListener
{
    private const STRING_TYPES = ['string', 'text'];

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $md = $em->getClassMetadata(get_class($entity));
            $this->cleanFields($uow, $md, $entity, $md->getFieldNames());
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $md = $em->getClassMetadata(get_class($entity));
            $this->cleanFields($uow, $md, $entity, array_keys($uow->getEntityChangeSet($entity)));
        }
    }

    private function cleanFields(UnitOfWork $uow, ClassMetadata $md, $entity, array $fields): void
    {
        $changed = false;
        foreach ($fields as $field) {
            if (!in_array($md->fieldMappings[$field]['type'] ?? null, self::STRING_TYPES, true)) {
                continue;
            }
            $value = $md->getFieldValue($entity, $field);
            if (!is_string($value)) {
                continue;
            }
            $clean = ParameterHandler::stripFourByteChars($value);
            if ($clean !== $value) {
                $md->setFieldValue($entity, $field, $clean);
                $changed = true;
            }
        }
        if ($changed) {
            $uow->recomputeSingleEntityChangeSet($md, $entity);
        }
    }
}
