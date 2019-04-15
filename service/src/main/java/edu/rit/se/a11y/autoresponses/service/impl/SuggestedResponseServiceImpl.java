package edu.rit.se.a11y.autoresponses.service.impl;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

import org.apache.logging.log4j.util.Strings;
import org.springframework.stereotype.Service;

import edu.rit.se.a11y.autoresponses.api.AddSuggestedResponseRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesResponse;
import edu.rit.se.a11y.autoresponses.model.ContextMessageResponseModel;
import edu.rit.se.a11y.autoresponses.service.SuggestedResponseService;

@Service
public class SuggestedResponseServiceImpl implements SuggestedResponseService {

    private List<ContextMessageResponseModel> contextMessageResponseModelList = new ArrayList<>();

    public SuggestedResponseServiceImpl() {
        initializeContextPhrasesAndResponses();
    }

    private void initializeContextPhrasesAndResponses() {
        ContextMessageResponseModel foodServiceContextMessageResponseModelObject = new ContextMessageResponseModel();
        foodServiceContextMessageResponseModelObject.setContext("food service");
        foodServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("what would you like to order", Arrays.asList("Could I please get a cheeseburger?", "Could I please get a chicken finger sandwich?"));
        foodServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("what would you like on that", Arrays.asList("Cheese and honey mustard, please.", "Nothing."));
        foodServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("anything to drink", Arrays.asList("Just water, please."));
        foodServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("here's your receipt", Arrays.asList("Thank you."));
        foodServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("your order should be ready", Arrays.asList("Thank you."));
        contextMessageResponseModelList.add(foodServiceContextMessageResponseModelObject);

        ContextMessageResponseModel movieServiceContextMessageResponseModelObject = new ContextMessageResponseModel();
        movieServiceContextMessageResponseModelObject.setContext("movie service");
        movieServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("what movie would you like to see", Arrays.asList("I would like to see"));
        movieServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("how many tickets", Arrays.asList("1 adult ticket and 1 child ticket."));
        movieServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("any snacks or drinks", Arrays.asList("Could I please get a chocolate bar?", "Could I please get a small popcorn?", "No thanks."));
        movieServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("here are your tickets", Arrays.asList("Thanks.", "Thank you."));
        movieServiceContextMessageResponseModelObject.addResponsesForIncomingMessage("have a good evening", Arrays.asList("Thanks, you too."));
        contextMessageResponseModelList.add(movieServiceContextMessageResponseModelObject);
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

    private List<String> getResponseFromContextAndPhrase(String incomingContext, String incomingPhrase) {
        List<String> suggestedResponses = new ArrayList<>();

        // Proceed only if the incoming context and phrase are not blank (i.e. not null, empty string or only whitespace)
        if (Strings.isNotBlank(incomingContext) && Strings.isNotBlank(incomingPhrase)) {
            // First get our list of context model objects. Next, using Java 8's Stream object, filter out any potential model objects that are null
            // or whose context is not equal to the incoming context (ignoring case). Then, for those model objects that were not filtered out,
            // return back a flattened list of the suggested responses for the incoming message, so that we don't return a list of lists.
            suggestedResponses = contextMessageResponseModelList.stream()
                .filter(cmrm -> cmrm != null && incomingContext.equalsIgnoreCase(cmrm.getContext()))
                .flatMap(cmrm -> cmrm.getSuggestedResponsesForIncomingMessage(incomingPhrase).stream())
                .collect(Collectors.toList());
        }

        return suggestedResponses;
    }

    @Override
    public void addSuggestedResponse(AddSuggestedResponseRequest addSuggestedResponseRequest) {
        if (addSuggestedResponseRequest != null) {
            String incomingContext = addSuggestedResponseRequest.getContext();
            String incomingMessage = addSuggestedResponseRequest.getMessage();
            String newSuggestedResponse = addSuggestedResponseRequest.getSuggestedResponse();

            // First see if an existing context model object exists for the incoming context
            Optional<ContextMessageResponseModel> optionalContextMessageResponseModelObject = contextMessageResponseModelList.stream()
                .filter(cmrm -> cmrm != null && incomingContext.equalsIgnoreCase(cmrm.getContext()))
                .findAny();

            // If there is a pre-existing context model object that matches the incoming context, then add the new suggested response for the
            // incoming message (whether pre-existing or new) to that model object. If there is no pre-existing context model object, then create a
            // new one and add it to our list of context model objects
            if (optionalContextMessageResponseModelObject.isPresent()) {
                optionalContextMessageResponseModelObject.get().addResponseForIncomingMessage(incomingMessage, newSuggestedResponse);
            } else {
                ContextMessageResponseModel newContextMessageResponseModelObject = new ContextMessageResponseModel();
                newContextMessageResponseModelObject.setContext(incomingContext);
                newContextMessageResponseModelObject.addResponseForIncomingMessage(incomingMessage, newSuggestedResponse);
                contextMessageResponseModelList.add(newContextMessageResponseModelObject);
            }
        }
    }

}
