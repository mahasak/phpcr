<?php

/**
 * This file is part of the PHPCR API and was originally ported from the Java
 * JCR API to PHP by Karsten Dambekalns for the FLOW3 project.
 *
 * Copyright 2008-2011 Karsten Dambekalns <karsten@typo3.org>
 *
 * This file in particular is derived from the Java UserTransaction Interface
 * of the package javax.transaction. For more information about the Java
 * interface have a look at
 * http://download.oracle.com/javaee/6/api/javax/transaction/package-summary.html
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache Software License 2.0
 * @link http://phpcr.github.com/
 */

namespace PHPCR\Transaction;

/**
 * As there is no transaction standard in PHP this interface should provide a
 * transaction mechanism in a way the original Java UserTransaction interface
 * can be used for transactions while working with the JCR API.
 *
 * Main differences to the original Java UserTransaction:
 * - The Java method getStatus() is named inTransaction()
 * - The Java method setRollbackOnly() is dropped
 * - Some exceptions specified by the Java spec are replaced by exceptions already
 *   specified by PHPCR:
 *   - NotSupportedException            ->   \PHPCR\UnsupportedRepositoryOperationException
 *   - SystemException                  ->   \PHPCR\RepositoryException
 * - New PHPCR exceptions specified by the Java spec:
 *   - RollbackException                ->   \PHPCR\Transaction\RollbackException
 *   - java.lang.SecurityException      ->   \PHPCR\AccessDeniedException
 *   - java.lang.IllegalStateException  ->   LogicException
 * - Some Java exceptions are dropped (HeuristicMixedException, HeuristicRollbackException)
 *
 * An implementation of this interface has to take care of that if a transaction
 * is startet every following request to the repository will be done in the
 * transactions context.
 *
 * It should also be possible to use this interface on a deeper level of a PHPCR
 * implementation e.g. that a $session->save() automatically starts and ends a
 * transaction before and after persisting all changes to the backend (if the
 * session is not yet in a transaction).
 *
 * @author Johannes Stark <starkj@gmx.de>
 * @package phpcr
 * @subpackage interfaces
 * @api
 */
interface UserTransactionInterface
{

    /**
     * Create a new transaction and associate it with the current session.
     *
     * @return void
     *
     * @throws \PHPCR\UnsupportedRepositoryOperationException Thrown if a transaction
     *      is already started and the transaction implementation or backend does not
     *      support nested transactions.
     *
     * @throws \PHPCR\RepositoryException Thrown if the transaction implementation
     *      encounters an unexpected error condition.
     */
    public function begin();

    /**
     *
     * Complete the transaction associated with the current session.
     *
     * @return void
     *
     * @throws \PHPCR\Transaction\RollbackException Thrown to indicate that the
     *      transaction has been rolled back rather than committed.
     * @throws \PHPCR\AccessDeniedException Thrown to indicate that the
     *      application is not allowed to commit the transaction.
     * @throws LogicException Thrown if the current session is not associated 
     *      with a transaction.
     * @throws \PHPCR\RepositoryException Thrown if the transaction implementation
     *      encounters an unexpected error condition.
     */
    public function commit();

    /**
     *
     * Obtain the status if the current session is inside of a transaction or not.
     *
     * @return boolean
     *
     * @throws \PHPCR\RepositoryException Thrown if the transaction implementation
     *      encounters an unexpected error condition.
     */
    public function inTransaction();

    /**
     *
     * Roll back the transaction associated with the current session.
     *
     * @return void
     *
     * @throws \PHPCR\AccessDeniedException Thrown to indicate that the
     *      application is not allowed to roll back the transaction.
     * @throws LogicException Thrown if the current session is not associated with
     *      a transaction.
     * @throws \PHPCR\RepositoryException Thrown if the transaction implementation
     *      encounters an unexpected error condition.
     */
    public function rollback();

    /**
     *
     * Modify the timeout value that is associated with the transaction started by
     * the current application with the begin method. If an application has not
     * called this method, the transaction service uses some default value for the
     * transaction timeout.
     *
     * @param int $seconds The value of the timeout in seconds. If the value is zero,
     *      the transaction service restores the default value. If the value is
     *      negative a RepositoryException is thrown.
     *
     * @return void
     *
     * @throws \PHPCR\RepositoryException Thrown if the transaction implementation
     *      encounters an unexpected error condition.
     */
    public function setTransactionTimeout($seconds = 0);

}
