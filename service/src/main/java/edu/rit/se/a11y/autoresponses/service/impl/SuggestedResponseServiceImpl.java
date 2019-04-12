package edu.rit.se.a11y.autoresponses.service.impl;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;
import org.apache.logging.log4j.util.Strings;
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
        contextPhrasesMap.put("food service", Arrays.asList("what would you like to order", "what would you like on that", "anything to drink",
                "that will be", "here's your receipt", "your order should be ready"));
        contextPhrasesMap.put("movie service", Arrays.asList("what movie would you like to see", "how many tickets", "any snacks or drinks",
                "that will be", "here are your tickets", "have a good evening"));

        contextSuggestedResponsesMap.put("food service", Arrays.asList("Could I please get a cheeseburger?",
                "Could I please get a chicken finger sandwich?", "Cheese and honey mustard, please.", "Just water, please.", "Thank you!"));
        contextSuggestedResponsesMap.put("movie service",
                Arrays.asList("I would like to see", "1 adult ticket and 1 child ticket.", "Could I please get a chocolate bar?",
                        "Could I please get a small popcorn?", "No thanks.", "Thanks, you too!", "Thanks!", "Thank you!"));
    }

    @Override
    public GetSuggestedResponsesResponse getSuggestedResponses(GetSuggestedResponsesRequest suggestedRequest) {
        // Set up a default, empty response object in case there is an issue with the
        // request body
        GetSuggestedResponsesResponse suggestedResponse = new GetSuggestedResponsesResponse(new ArrayList<>());

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
