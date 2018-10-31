<?php
namespace ZealByte\Identity\Catalog
{
	use Doctrine\Bundle\DoctrineBundle\Registry;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type as FormType;
	use ZealByte\Catalog\Form\Extension\Catalog\Type as FilterType;
	use ZealByte\Catalog\Data\Type as DataType;
	use ZealByte\Catalog\Column\Type as ColumnType;
	use ZealByte\Catalog\CatalogBuilderInterface;
	use ZealByte\Catalog\CatalogMapperInterface;
	use ZealByte\Catalog\Datum;
	use ZealByte\Catalog\Field;
	use ZealByte\Catalog\SpecAbstract;
	use ZealByte\Catalog\Data\Source\DoctrineRegistry;
	use ZealByte\Identity\Entity\IdentityRecover;

	class IdentityRecoverSpec extends SpecAbstract
	{
		private $doctrine;

		public function __construct (Registry $doctrine)
		{
			$this->doctrine = $doctrine;
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildCatalogMap (CatalogMapperInterface $mapper) : void
		{
			$source = (new DoctrineRegistry())
				->setEntityManager($this->doctrine->getManager())
				->setEntityId(IdentityRecover::class);

			$mapper
				->setDataSource($source)
				->setIdentifierField(new Field('id', '[id]', DataType\UUIDType::class))
				->setLabelField(new Field('dateRequested', '[dateRequested]', DataType\DateTimeType::class))
				->addField(new Field('userId', '[userId]', DataType\StringType::class))
				->addField(new Field('key', '[key]', DataType\StringType::class))
				->addField(new Field('code', '[code]', DataType\StringType::class))
				->addField(new Field('attempts', '[attempts]', DataType\IntegerType::class))
				->addField(new Field('revoked', '[revoked]', DataType\BooleanType::class))
				->addField(new Field('status', '[status]', DataType\StringType::class));
		}

		/**
		 * {@inheritdoc}
		 */
		public function buildCatalogView (CatalogBuilderInterface $builder) : void
		{
			$builder
				->addDatum($this->getDateRequestedDatum());
		}

		private function getDateRequestedDatum () : Datum
		{
			return (new Datum('date_requested', ['dateRequested']))
				->setColumnType(ColumnType\DateTimeColumnType::class, [
					'defaultContent' => 'Never',
					'format' => 'm/d/Y',
					'searchable' => false,
				])
				->setFormType(FormType\DateTimeType::class, [
					'date_widget' => 'single_text',
					'time_widget' => 'single_text',
				]);
		}

	}
}
