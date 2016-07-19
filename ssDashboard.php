<?php
require_once 'login.php';
?>
<h1>Smart Signal Text Analytics </h1>

<h3>Analysis</h3>
<ol>	
	<li><a href="ngram%20analysis/">N-Gram Analysis</a></li>
	<li><a href="generateDotGraph.php">Collocation Graph</a></li>
	<li><a href="lda/vis">LDA Topic Modelling</a></li>
	<li><a href="kmeans/kmeans_clustering_SS.html">KMeans Clustering</a></li>
	<li><a href="displaySimilarity.php">Document Similarity</a></li>
</ol>
<hr/>
<h3>DB related tasks in the correct order</h3>
<ol>
	<!-- <li><a href="insertCasesToDBWithoutCleanup.php">Insert the raw cases in the DB</a></li> -->
	<li><a href="tokenizeCaseDataUsingDictionary.php">Insert raw case and Tokenized case using Dictionary</a></li>
	<li><a href="insertUniqueTokensForAssociationAnalysis.php">Get Unique Tokens for Association Analysis</a></li>
	<li><a href="insertNGramsToDB.php">Insert NGrams</a></li>
	<li><a href="addAssociatedAssetsAndConditionsToDB.php">Add Association Analysis to DB</a></li>
	<li><a href="cosineSimilarityDump.php">Add Cosine Similarity and Cluster Analysis to DB</a></li>
</ol>
<hr/>
<h3>Additional important tasks</h3>
<ol>
	
	<li><a href="convertCSVToARFF.php">Convert From CSV to ARFF</a></li>
	<li><a href="clusterSimilarityTest.php">Test How different is Document Similarity & Clustering</a></li>
</ol>
<hr/>

