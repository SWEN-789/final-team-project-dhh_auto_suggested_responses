package edu.rit.se.a11y.autoresponses.model;

import java.util.ArrayList;
import java.util.List;
import java.util.ListIterator;
import java.util.stream.Collectors;

import org.apache.commons.lang3.StringUtils;

public class ContextMessageResponseModel {

    private String context;
    private List<MessageWithResponsesModel> messagesWithResponsesModelList;

    public ContextMessageResponseModel() {
    }

    public ContextMessageResponseModel(String context, List<MessageWithResponsesModel> messagesWithResponsesModelList) {
        this.context = context;
        this.messagesWithResponsesModelList = messagesWithResponsesModelList;
    }

    public String getContext() {
        return context;
    }

    public void setContext(String context) {
        this.context = context;
    }

    public List<MessageWithResponsesModel> getMessagesWithResponsesModelList() {
        if (messagesWithResponsesModelList == null) {
            messagesWithResponsesModelList = new ArrayList<>();
        }
        return messagesWithResponsesModelList;
    }

    public void setMessagesWithResponsesModelList(List<MessageWithResponsesModel> messagesWithResponsesModelList) {
        this.messagesWithResponsesModelList = messagesWithResponsesModelList;
    }

    public void addResponsesForIncomingMessage(String incomingMessage, List<String> incomingSuggestedResponses) {
        for (String incomingSuggestedResponse : incomingSuggestedResponses) {
            addResponseForIncomingMessage(incomingMessage, incomingSuggestedResponse);
        }
    }

    public void addResponseForIncomingMessage(String incomingMessage, String incomingSuggestedResponse) {
        // Proceed only if the incoming message and suggested response are not blank (i.e. not null, empty string or only whitespace)
        if (StringUtils.isNotBlank(incomingMessage) && StringUtils.isNotBlank(incomingSuggestedResponse)) {
            ListIterator<MessageWithResponsesModel> messagesWithResponsesModelListIterator = getMessagesWithResponsesModelList().listIterator();
            boolean wereMessageSuggestedResponseAdded = false;

            // Iterate over the list of our model objects while we still haven't added the incoming message and/or suggested response
            while (!wereMessageSuggestedResponseAdded && messagesWithResponsesModelListIterator.hasNext()) {
                MessageWithResponsesModel currentMessageWithResponsesModelObject = messagesWithResponsesModelListIterator.next();
                String currentMessage = currentMessageWithResponsesModelObject.getMessage();

                // If the incoming message is similar to the current pre-existing message (i.e. incoming message contains substring of current
                // pre-existing message, ignoring case), then add just the incoming suggested response since the (similar) message already exists
                if (StringUtils.containsIgnoreCase(incomingMessage, currentMessage)) {
                    currentMessageWithResponsesModelObject.addSuggestedResponse(incomingSuggestedResponse);

                    // Indicate that we successfully added the incoming suggested response so that we can stop iterating over the list of our model
                    // objects
                    wereMessageSuggestedResponseAdded = true;
                }
            }

            // If the incoming message was not similar to any pre-existing messages, then create a new model object with the incoming message and
            // suggested response and add the model object to our list of model objects
            if (!wereMessageSuggestedResponseAdded) {
                MessageWithResponsesModel newMessageWithResponsesModelObject = new MessageWithResponsesModel();
                newMessageWithResponsesModelObject.setMessage(incomingMessage);
                newMessageWithResponsesModelObject.addSuggestedResponse(incomingSuggestedResponse);
                messagesWithResponsesModelListIterator.add(newMessageWithResponsesModelObject);
                wereMessageSuggestedResponseAdded = true;
            }
        }
    }

    public List<String> getSuggestedResponsesForIncomingMessage(String incomingMessage) {
        List<String> suggestedResponsesForIncomingMessage = new ArrayList<>();

        // Proceed only if the incoming message is not blank (i.e. not null, empty string or only whitespace)
        if (StringUtils.isNotBlank(incomingMessage)) {
            // First get our list of model objects. Next, using Java 8's Stream object, filter out any potential model objects that are null or whose
            // message is not a substring of the incoming message (ignoring case). Then, for those model objects that were not filtered out, return back
            // a flattened list of the suggested responses for the incoming message, so that we don't return a list of lists.
            suggestedResponsesForIncomingMessage = getMessagesWithResponsesModelList()
                    .stream()
                    .filter(mwrm -> mwrm != null && StringUtils.containsIgnoreCase(incomingMessage, mwrm.getMessage()))
                    .flatMap(mwrm -> mwrm.getSuggestedResponses().stream())
                    .collect(Collectors.toList());

        }

        return suggestedResponsesForIncomingMessage;
    }

}
