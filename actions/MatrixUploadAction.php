<?php
/*
 * @author ikirux
 */
class MatrixUploadAction extends CAction {
    /**
     * Name of the model attribute used to store the original file name.
     * Defaults to 'filename', the default value in MatrixUploadAction
     * @see MatrixUploadAction::run()     
     * @var string
     */
    public $fileNameAttribute = '';

    /**
     * Name of the model attribute used to store the internal file filesystem name.
     * Defaults to 'filename', the default value in MatrixUploadAction
     * @see MatrixUploadAction::run()     
     * @var string
     */
    public $fileInternalAttribute = '';    

    /**
     * Path of the main uploading folder.
     * @see MatrixUploadAction::init()
     * @var string
     */
    public $path;

    /**
     * Public path of the main uploading folder, relative to Yii::app()->getBasePath().
     * @var string
     */
    public $publicPath;

    /**
     * Public subfolder path of the main uploading folder.
     * @see MatrixUploadAction::init()
     * @var string
     */
    public $_subfolder;

    /**
     * The object form model we'll be saving our files to
     * @see MatrixUploadAction::init()     
     * @var CModel (or subclass)
     */
    private $_formModel;

    /**
     * MatrixUploadForm (or subclass of it) to be used.  Defaults to MatrixUploadForm
     * @see MatrixUploadAction::init()
     * @var string
     */
    public $formClass;

    /**
     * Name of the model attribute referring to the uploaded file object.
     * Defaults to 'file', the default value in MatrixUploadForm
     * @see MatrixUploadAction::handleUploading()     
     * @var string
     */
    public $fileAttribute = 'file';

    /**
     * Name of the model attribute used to store mimeType information.
     * Defaults to 'mime_type', the default value in MatrixUploadForm
     * @see MatrixUploadAction::handleUploading()       
     * @var string
     */
    public $mimeTypeAttribute = 'mime_type';

    /**
     * Name of the model attribute used to store file size.
     * Defaults to 'size', the default value in MatrixUploadForm
     * @see MatrixUploadAction::handleUploading()       
     * @var string
     */
    public $sizeAttribute = 'size';

    /**
     * Main method
     * @author ikirux
     */
    public function run($fileNameAttribute, $fileInternalAttribute)
    {
        // we get atributtes to process
        $this->fileNameAttribute = $fileNameAttribute;
        $this->fileInternalAttribute = $fileInternalAttribute;

        $this->handleUploading();
    }

    /**
     * Uploads file to temporary directory
     *
     * @throws CHttpException
     */
    protected function handleUploading() 
    {
        $this->init();
        $model = $this->_formModel;
        $model->{$this->fileAttribute} = CUploadedFile::getInstance($model, $this->fileNameAttribute);

        if ($model->{$this->fileAttribute} !== null) {
            $model->{$this->mimeTypeAttribute} = $model->{$this->fileAttribute}->getType();
            $model->{$this->sizeAttribute} = $model->{$this->fileAttribute}->getSize();

            // Original uploading name
            $model->{$this->fileNameAttribute} = $model->{$this->fileAttribute}->getName();

            // Name used to store the saved file name
            $model->{$this->fileInternalAttribute} = sha1(Yii::app()->user->id . microtime()) . $model->{$this->fileNameAttribute};
            
            $model->scenario = 'uploadingFile';
            if ($model->validate([$this->fileNameAttribute])) {
                $path = $this->getPath();

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }  
                
                // Save the uploading file in temporal directory
                $model->{$this->fileAttribute}->saveAs($path . $model->{$this->fileInternalAttribute});
                chmod($path . $model->{$this->fileInternalAttribute}, 0777);

                // Look out for previous uploded files
                $files = [];
                if (Yii::app()->user->hasState('MatrixUploadFiles')) {
                    $files = Yii::app()->user->getState('MatrixUploadFiles');
                } 

                $files[$this->fileNameAttribute] = [
                    'mime' => $this->_formModel->{$this->mimeTypeAttribute},                
                    'size' => $this->_formModel->{$this->sizeAttribute},
                    'fileName' => $this->_formModel->{$this->fileNameAttribute},
                    'fileInternalName' => $this->_formModel->{$this->fileInternalAttribute},
                    'temporalPath' => $path,
                    'publicPath' => $this->publicPath,
                    'fileInternalAttribute' => $this->fileInternalAttribute,
                    'fileNameAttribute' => $this->fileNameAttribute,
                ];

                Yii::app()->user->setState('MatrixUploadFiles', $files);
            } else {
                throw new CHttpException(500, $model->getError($this->fileNameAttribute));
            }
        } else {
            throw new CHttpException(500, "Could not upload file");
        }
    }

    /**
     * Initialize the propeties of this action, if they are not set.
     *
     */
    public function init( ) 
    {
        if (!isset($this->path)) {
            $this->path = realpath(Yii::app()->getBasePath() . "/../uploads");
        }

        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
            chmod($this->path , 0777);
        } else if(!is_writable($this->path)) {
            chmod($this->path, 0777);
        }

        $publicPath = Yii::app()->getBasePath() . $this->publicPath;
        if (!is_dir($publicPath)) {
            mkdir($publicPath, 0777, true);
            chmod($publicPath , 0777);
        } else if(!is_writable($publicPath)) {
            chmod($publicPath, 0777);
        }

        $this->_subfolder = date("mdY");

        if (!isset($this->_formModel)) {
            $this->_formModel = Yii::createComponent(['class' => $this->formClass]);
        }
    }

    /**
     * Returns the file's path on the filesystem
     * @return string
     */
    protected function getPath() 
    {
        $path = ($this->_subfolder != "") ? "{$this->path}/{$this->_subfolder}/" : "{$this->path}/";
        return $path;
    }
}