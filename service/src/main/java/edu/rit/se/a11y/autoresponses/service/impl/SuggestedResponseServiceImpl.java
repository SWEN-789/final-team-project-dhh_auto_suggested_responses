package edu.rit.se.a11y.autoresponses.service.impl;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import java.util.Properties;

import edu.stanford.nlp.ling.CoreAnnotations;
import edu.stanford.nlp.pipeline.Annotation;
import edu.stanford.nlp.pipeline.StanfordCoreNLP;
import edu.stanford.nlp.sentiment.SentimentCoreAnnotations;
import edu.stanford.nlp.trees.Tree;
import edu.stanford.nlp.util.CoreMap;
import edu.stanford.nlp.neural.rnn.RNNCoreAnnotations;



import org.apache.commons.lang3.StringUtils;
import org.apache.logging.log4j.util.Strings;
import org.ejml.simple.SimpleMatrix;
import org.springframework.stereotype.Service;

import edu.rit.se.a11y.autoresponses.api.AddSuggestedResponseRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesResponse;
import edu.rit.se.a11y.autoresponses.service.SuggestedResponseService;

@Service
public class SuggestedResponseServiceImpl implements SuggestedResponseService {

    private Map<String, List<String>> contextPhrasesMap = new HashMap<>();
    private Map<String, List<String>> contextSuggestedResponsesMap = new HashMap<>();

    public SuggestedResponseServiceImpl() {
        initializeContextPhrasesAndResponses();
    }

    private void initializeContextPhrasesAndResponses() {
        contextPhrasesMap.put("food service", Arrays.asList("what would you like to order"));

        contextSuggestedResponsesMap.put("food service", Arrays.asList("Could I please get a cheeseburger?", "Could I please get a chicken sandwich?"));
    }

    @Override
    public GetSuggestedResponsesResponse getSuggestedResponses(GetSuggestedResponsesRequest suggestedRequest) {
        // Set up a default, empty response object in case there is an issue with the request body
        GetSuggestedResponsesResponse suggestedResponse = new GetSuggestedResponsesResponse(new ArrayList<>());

        Properties pipelineProps = new Properties();
        Properties tokenizerProps = new Properties();
        pipelineProps.setProperty("annotators", "parse, sentiment");
        pipelineProps.setProperty("parse.binaryTrees", "true");
        pipelineProps.setProperty("enforceRequirements", "false");
        tokenizerProps.setProperty("annotators", "tokenize, ssplit, pos, lemma, ");
        StanfordCoreNLP tokenizer = new StanfordCoreNLP(tokenizerProps);
        StanfordCoreNLP pipeline = new StanfordCoreNLP(pipelineProps);
        String line = suggestedRequest.getMessage();
        System.out.println(tokenizer);
        Annotation annotation = tokenizer.process(line);
        System.out.println(annotation);
        pipeline.annotate(annotation);
        // normal output
        for(CoreMap sentence: annotation.get(CoreAnnotations.SentencesAnnotation.class)) {

            System.out.println(sentence);

            Tree tree = sentence.get(SentimentCoreAnnotations.SentimentAnnotatedTree.class);

            System.out.println(tree);

            SimpleMatrix simpleMatrix = RNNCoreAnnotations.getPredictions(tree);

            System.out.println(simpleMatrix);
            System.out.println(sentence);
        }


            System.out.println( "End of Processing" );
        if (suggestedRequest != null) {
            String context = suggestedRequest.getContext();
            String message = suggestedRequest.getMessage();
            List<String> suggestedResponses = getResponseFromContextAndPhrase(context, message);
            suggestedResponse = new GetSuggestedResponsesResponse(suggestedResponses);
        }

        return suggestedResponse;
    }

    private List<String> getResponseFromContextAndPhrase(String context, String phrase) {
        List<String> suggestedResponses = new ArrayList<>();

        if (Strings.isNotBlank(context) && Strings.isNotBlank(phrase)) {
            List<String> phrasesForContext = contextPhrasesMap.get(context);
            if (phrasesForContext != null && phrasesForContext.stream().anyMatch(s -> StringUtils.containsIgnoreCase(phrase, s))) {
                suggestedResponses = contextSuggestedResponsesMap.get(context);
            }
        }

        return suggestedResponses;
    }

    @Override
    public void addSuggestedResponse(AddSuggestedResponseRequest addSuggestedResponseRequest) {
        if (addSuggestedResponseRequest != null) {
            String context = addSuggestedResponseRequest.getContext();
            String newSuggestedResponse = addSuggestedResponseRequest.getSuggestedResponse();
            List<String> existingSuggestedResponses = contextSuggestedResponsesMap.get(context);
            existingSuggestedResponses = existingSuggestedResponses != null ? new ArrayList<>(existingSuggestedResponses) : new ArrayList<>();
            existingSuggestedResponses.add(newSuggestedResponse);
            contextSuggestedResponsesMap.put(context, existingSuggestedResponses);
        }
    }

}
